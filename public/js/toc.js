// Índice lateral de las páginas legales: resalta la sección visible y desplaza suavemente al pulsar.
document.addEventListener("DOMContentLoaded", function () {
    const links = Array.from(document.querySelectorAll(".ct-toc a[href^='#']"));
    if (links.length === 0) return;

    const sections = links
        .map(link => document.querySelector(link.getAttribute("href")))
        .filter(Boolean);

    function setActive(id) {
        links.forEach(l => l.classList.toggle("is-active", l.getAttribute("href") === "#" + id));
    }

    links.forEach(link => link.addEventListener("click", function (event) {
        const target = document.querySelector(link.getAttribute("href"));
        if (!target) return;
        event.preventDefault();
        target.scrollIntoView({ behavior: "smooth", block: "start" });
        history.replaceState(null, "", link.getAttribute("href"));
        setActive(target.id);
    }));

    if ("IntersectionObserver" in window) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) setActive(entry.target.id);
            });
        }, { rootMargin: "-15% 0px -70% 0px" });
        sections.forEach(s => observer.observe(s));
    }

    setActive(sections[0].id);
});
