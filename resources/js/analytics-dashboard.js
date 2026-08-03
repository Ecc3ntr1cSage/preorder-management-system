const ELASTIC = "cubic-bezier(0.32, 0.72, 0, 1)";
const ELASTIC_SOFT = "cubic-bezier(0.34, 1.56, 0.64, 1)";

document.addEventListener("DOMContentLoaded", () => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const delay = parseInt(el.dataset.delay || "0", 10);

                    el.style.transitionDelay = `${delay}ms`;
                    el.style.transitionTimingFunction = ELASTIC;
                    el.style.transitionDuration = "900ms";

                    el.style.transform = "translateY(0) rotate(0deg) scale(1)";
                    el.style.opacity = "1";

                    observer.unobserve(el);
                }
            });
        },
        { threshold: 0.05 },
    );

    const animatedElements = document.querySelectorAll("[data-animate='fade-up']");

    animatedElements.forEach((el) => {
        const rotation = el.style.rotate;
        el.style.transform = `translateY(2rem)${rotation ? ` rotate(${rotation})` : ""}`;
        el.style.opacity = "0";
        el.style.transition = "none";

        observer.observe(el);
    });

    const progressBars = document.querySelectorAll("[style*='width:']");
    let animated = false;

    const progressObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting && !animated) {
                    animated = true;
                    const bar = entry.target;
                    const targetWidth = bar.style.width;
                    bar.style.width = "0%";
                    bar.style.transition = `width 1200ms ${ELASTIC}`;
                    setTimeout(() => {
                        bar.style.width = targetWidth;
                    }, 150);
                }
            });
        },
        { threshold: 0.3 },
    );

    document.querySelectorAll(".h-full.rounded-full").forEach((bar) => {
        progressObserver.observe(bar.closest("div"));
    });

    const statCards = document.querySelectorAll(
        ".relative.rounded-\\[2rem\\].bg-white.p-1\\.5",
    );

    statCards.forEach((card) => {
        card.addEventListener("mouseenter", () => {
            card.style.transform = "translateY(-6px)";
            card.style.transition = `transform 400ms ${ELASTIC_SOFT}`;
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "translateY(0)";
        });
    });
});
