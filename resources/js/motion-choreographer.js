const ELASTIC = "cubic-bezier(0.32, 0.72, 0, 1)";
const ELASTIC_SOFT = "cubic-bezier(0.34, 1.56, 0.64, 1)";

const observerOptions = {
  root: null,
  rootMargin: "0px",
  threshold: 0.05,
};

const animateOnIntersection = (entries, observer) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      const el = entry.target;
      const delay = parseInt(el.dataset.delay || "0", 10);

      el.style.transitionDelay = `${delay}ms`;
      el.style.transitionTimingFunction = ELASTIC;
      el.style.transitionProperty = "transform, opacity, backdrop-filter";
      el.style.transitionDuration = "800ms";

      el.style.transform = "translateY(0) scale(1)";
      el.style.opacity = "1";

      observer.unobserve(el);
    }
  });
};

const scrollObserver = new IntersectionObserver(
  animateOnIntersection,
  observerOptions,
);

document.addEventListener("DOMContentLoaded", () => {
  const animatedElements = document.querySelectorAll("[data-animate='fade-up']");

  animatedElements.forEach((el) => {
    el.style.transform = "translateY(2rem)";
    el.style.opacity = "0";
    el.style.transition = "none";

    scrollObserver.observe(el);
  });

  const goldButtons = document.querySelectorAll(
    "a[class*='bg-ink'], button[class*='bg-gold']",
  );

  goldButtons.forEach((btn) => {
    btn.classList.add("hover:scale-95");

    const icon = btn.querySelector("span:last-child");
    if (icon && icon.textContent.includes("↗")) {
      btn.addEventListener("mouseenter", () => {
        icon.style.transform = "translate(2px, -2px) scale(1.1)";
        icon.style.transition = `transform 300ms ${ELASTIC_SOFT}`;
      });
      btn.addEventListener("mouseleave", () => {
        icon.style.transform = "translate(0) scale(1)";
      });
    }

    btn.addEventListener("mousedown", () => {
      btn.style.transform = "scale(0.97)";
    });
    btn.addEventListener("mouseup", () => {
      btn.style.transform = "scale(1)";
    });
  });
});
