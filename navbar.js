const root = document.documentElement;
        const nav = document.querySelector('.nav');
        const menu = document.querySelector('.menu');
        const btn = document.querySelector('.hamburger');
        const links = document.querySelectorAll('.menu a');
        const mq = matchMedia('(width > 600px)');
        const offset = getComputedStyle(root).getPropertyValue('--offset');
        let toggle;

        const reset = () => mq.matches && nav.classList.remove('animation');

        function tween(el, direction) {
            return el.animate(
                [
                    {
                        transform: `translate(${offset})`,
                        opacity: 0,
                    },
                    {
                        transform: `translate(0)`,
                        opacity: 1,
                    }
                ],
                {
                    duration: 750,
                    fill: 'both',
                    easing: 'ease-out',
                    delay: `${el.dataset.delay * 250}`,
                    direction,
                }
            );
        }

        function init() {
            let direction = (toggle = !toggle) ? 'normal' : 'reverse';
            if (!mq.matches) direction = 'normal';
            links.forEach((link) => tween(link, direction));
            nav.classList.toggle('animation');
        }

        btn.addEventListener('click', init, false);
        window.addEventListener('resize', reset, false);