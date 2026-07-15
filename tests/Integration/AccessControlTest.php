<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * Tests d'intégration du contrôle d'accès, via HTTP sur l'application réelle.
 *
 * Ils verrouillent trois régressions qui ont réellement eu lieu :
 *   - le cookie de session n'était jamais posé (connexion impossible) ;
 *   - les pages d'administration étaient accessibles à tous ;
 *   - le tri de globalView.php était injectable par l'URL.
 *
 * Nécessitent l'application démarrée. Ignorés si BASE_URL n'est pas défini :
 *   BASE_URL=http://localhost:8080 vendor/bin/phpunit --testsuite integration
 */
final class AccessControlTest extends TestCase
{
    /** Pages réservées aux administrateurs. */
    private const ADMIN_PAGES = [
        'admin.php',
        'usersManage.php',
        'globalView.php',
        'classesManage.php',
        'newsCreate.php',
        'addMark.php',
        'addSubject.php',
        'addClasses.php',
        'selectClasses.php',
        'createUser.php',
        'planningManager.php',
        'editUser.php',
        'modifyClasses.php',
    ];

    private string $baseUrl;

    protected function setUp(): void
    {
        $baseUrl = getenv('BASE_URL');

        if ($baseUrl === false || $baseUrl === '') {
            self::markTestSkipped('BASE_URL non défini : application non démarrée.');
        }

        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function testLoginSetsSessionCookie(): void
    {
        $session = $this->login('admin', 'admin');

        self::assertNotSame('', $session, 'Aucun cookie PHPSESSID renvoyé par login.php.');
    }

    public function testWrongPasswordShowsErrorAndGrantsNoSession(): void
    {
        $response = $this->request('login.php', [
            'method' => 'POST',
            'body' => http_build_query(['login-username' => 'admin', 'login-passwd' => 'faux']),
        ]);

        self::assertStringContainsString('Identifiant ou mot de passe incorrect.', $response['body']);
        self::assertStringNotContainsString('Connexion Réussie', $response['body']);
    }

    public function testAuthenticatedUserReachesHomePage(): void
    {
        $session = $this->login('user', 'user');

        $response = $this->request('index.php', ['session' => $session]);

        self::assertStringContainsString('Bienvenue sur ProGes', $response['body']);
    }

    public function testAnonymousVisitorIsRejectedFromHomePage(): void
    {
        $response = $this->request('index.php');

        self::assertStringContainsString("Veuillez vous connecter", $response['body']);
    }

    /**
     * @dataProvider adminPagesProvider
     */
    public function testAdminReachesAdminPage(string $page): void
    {
        $session = $this->login('admin', 'admin');

        $response = $this->request($page, ['session' => $session]);

        self::assertSame(200, $response['status'], "L'admin devrait accéder à {$page}.");
    }

    /**
     * @dataProvider adminPagesProvider
     */
    public function testStandardUserIsRedirectedFromAdminPage(string $page): void
    {
        $session = $this->login('user', 'user');

        $response = $this->request($page, ['session' => $session]);

        self::assertSame(302, $response['status'], "{$page} doit être interdit à un simple utilisateur.");
    }

    /**
     * @dataProvider adminPagesProvider
     */
    public function testAnonymousVisitorIsRedirectedFromAdminPage(string $page): void
    {
        $response = $this->request($page);

        self::assertSame(302, $response['status'], "{$page} doit être interdit à un visiteur anonyme.");
    }

    /**
     * @dataProvider adminPagesProvider
     */
    public function testAdminPageLeaksNoContentToStandardUser(string $page): void
    {
        $session = $this->login('user', 'user');

        $response = $this->request($page, ['session' => $session]);

        self::assertSame(
            '',
            trim($response['body']),
            "{$page} ne doit envoyer aucun contenu à un utilisateur non autorisé."
        );
    }

    public function testSortOrderInjectionIsNeutralised(): void
    {
        $session = $this->login('admin', 'admin');

        $response = $this->request(
            'globalView.php?sortOrder=' . rawurlencode('mark; DROP TABLE users--'),
            ['session' => $session]
        );

        self::assertSame(200, $response['status'], 'La charge injectée ne doit pas provoquer d\'erreur SQL.');
    }

    public function testLogoutInvalidatesSession(): void
    {
        $session = $this->login('user', 'user');

        $this->request('deconnexion.php', ['session' => $session]);
        $response = $this->request('index.php', ['session' => $session]);

        self::assertStringContainsString('Veuillez vous connecter', $response['body']);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function adminPagesProvider(): array
    {
        $cases = [];
        foreach (self::ADMIN_PAGES as $page) {
            $cases[$page] = [$page];
        }

        return $cases;
    }

    /**
     * Authentifie un utilisateur et retourne son identifiant de session.
     */
    private function login(string $username, string $password): string
    {
        $response = $this->request('login.php', [
            'method' => 'POST',
            'body' => http_build_query([
                'login-username' => $username,
                'login-passwd' => $password,
            ]),
        ]);

        foreach ($response['headers'] as $header) {
            if (preg_match('/^Set-Cookie:\s*(PHPSESSID=[^;]+)/i', $header, $matches) === 1) {
                return $matches[1];
            }
        }

        return '';
    }

    /**
     * @param array{method?: string, body?: string, session?: string} $options
     *
     * @return array{status: int, headers: array<int, string>, body: string}
     */
    private function request(string $path, array $options = []): array
    {
        $headers = ['Content-Type: application/x-www-form-urlencoded'];

        if (isset($options['session']) && $options['session'] !== '') {
            $headers[] = 'Cookie: ' . $options['session'];
        }

        $context = stream_context_create([
            'http' => [
                'method' => $options['method'] ?? 'GET',
                'header' => implode("\r\n", $headers),
                'content' => $options['body'] ?? '',
                'follow_location' => 0,
                'ignore_errors' => true,
                'timeout' => 10,
            ],
        ]);

        // Renseignée par le gestionnaire de flux HTTP à chaque requête ;
        // initialisée ici pour rester définie si la requête échoue.
        $http_response_header = [];

        $body = @file_get_contents($this->baseUrl . '/' . $path, false, $context);

        return [
            'status' => $this->statusFrom($http_response_header),
            'headers' => $http_response_header,
            'body' => $body === false ? '' : $body,
        ];
    }

    /**
     * @param array<int, string> $headers
     */
    private function statusFrom(array $headers): int
    {
        foreach ($headers as $header) {
            if (preg_match('#^HTTP/\S+\s+(\d{3})#', $header, $matches) === 1) {
                return (int) $matches[1];
            }
        }

        return 0;
    }
}
