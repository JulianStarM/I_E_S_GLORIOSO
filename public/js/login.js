/* Particle network effect - Login Banco de Libros GSC */
(function () {
    const canvas = document.getElementById('particles');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    let w, h, particles = [];
    const COUNT    = 90;
    const MAX_DIST = 130;
    const COLOR    = 'rgba(244,162,97,';   // accent dorado
    const LINE     = 'rgba(255,255,255,';

    function resize() {
        w = canvas.width  = window.innerWidth;
        h = canvas.height = window.innerHeight;
    }

    function init() {
        particles = [];
        for (let i = 0; i < COUNT; i++) {
            particles.push({
                x: Math.random() * w,
                y: Math.random() * h,
                vx: (Math.random() - .5) * .6,
                vy: (Math.random() - .5) * .6,
                r: Math.random() * 1.8 + .8,
            });
        }
    }

    function step() {
        ctx.clearRect(0, 0, w, h);

        for (const p of particles) {
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > w) p.vx *= -1;
            if (p.y < 0 || p.y > h) p.vy *= -1;

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = COLOR + '0.9)';
            ctx.fill();
        }

        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const a = particles[i], b = particles[j];
                const dx = a.x - b.x, dy = a.y - b.y;
                const d = Math.hypot(dx, dy);
                if (d < MAX_DIST) {
                    ctx.strokeStyle = LINE + (1 - d / MAX_DIST) * 0.25 + ')';
                    ctx.lineWidth = .8;
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.stroke();
                }
            }
        }
        requestAnimationFrame(step);
    }

    window.addEventListener('resize', () => { resize(); init(); });
    resize(); init(); step();
})();
