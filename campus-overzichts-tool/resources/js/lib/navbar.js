function randomlogocolor() {
    const logo = document.getElementById('navlogo');

    if (logo) {
        logo.style.backgroundColor = `rgb(${Math.round(Math.random() * 255)}, ${Math.round(Math.random() * 255)}, ${Math.round(Math.random() * 255)})`;
    }
}

function initnavbar() {
    const navbody = document.getElementById('navbody');
    const displacement = document.getElementById('displacement');

    if (!navbody || !displacement) return;

    const updateDisplacementHeight = () => {
        displacement.style.height = `${navbody.getBoundingClientRect().height}px`;
    };

    updateDisplacementHeight();
    new ResizeObserver(updateDisplacementHeight).observe(navbody);

    setInterval(randomlogocolor, 3000);
}

initnavbar();
