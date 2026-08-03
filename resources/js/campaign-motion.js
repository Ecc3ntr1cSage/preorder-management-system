const ELASTIC = "cubic-bezier(0.32, 0.72, 0, 1)";
const ELASTIC_SOFT = "cubic-bezier(0.34, 1.56, 0.64, 1)";

document.addEventListener("DOMContentLoaded", () => {
  const animatedElements = document.querySelectorAll("[data-animate='fade-up']");

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const el = entry.target;
          const delay = parseInt(el.dataset.delay || "0", 10);

          el.style.transitionDelay = `${delay}ms`;
          el.style.transitionTimingFunction = ELASTIC;
          el.style.transitionDuration = "800ms";

          el.style.transform = "translateY(0) rotate(0deg) scale(1)";
          el.style.opacity = "1";

          observer.unobserve(el);
        }
      });
    },
    { threshold: 0.05 },
  );

  animatedElements.forEach((el) => {
    const isRotated = el.classList.contains("rotate-2");
    el.style.transform = `translateY(2rem)${isRotated ? " rotate(2deg)" : ""}`;
    el.style.opacity = "0";
    el.style.transition = "none";

    observer.observe(el);
  });

  const hamburger = document.getElementById("hamburger");
  const mobileMenu = document.getElementById("mobile-menu");

  if (hamburger && mobileMenu) {
    let isOpen = false;

    hamburger.addEventListener("click", () => {
      if (isOpen) {
        hamburger.classList.remove("open");
        mobileMenu.classList.add("closed");
        mobileMenu.classList.remove("open");
        isOpen = false;
      } else {
        hamburger.classList.add("open");
        mobileMenu.classList.remove("closed");
        mobileMenu.classList.add("open");
        isOpen = true;
      }
    });
  }

  const glassButtons = document.querySelectorAll(".group[href], .group button");

  glassButtons.forEach((btn) => {
    btn.addEventListener("mouseenter", () => {
      const trailingIcon = btn.querySelector("span:last-child");
      if (trailingIcon) {
        trailingIcon.style.transform = "translate(3px, -1px) scale(1.1)";
        trailingIcon.style.transition = `transform 300ms ${ELASTIC_SOFT}`;
      }
    });
    btn.addEventListener("mouseleave", () => {
      const trailingIcon = btn.querySelector("span:last-child");
      if (trailingIcon) {
        trailingIcon.style.transform = "translate(0) scale(1)";
      }
    });
    btn.addEventListener("mousedown", () => {
      btn.style.transform = "scale(0.97)";
    });
    btn.addEventListener("mouseup", () => {
      btn.style.transform = "scale(1)";
    });
  });

  const parallaxElements = document.querySelectorAll("[data-parallax]");
  if (parallaxElements.length > 0) {
    const handleScroll = () => {
      const scrolled = window.scrollY;
      parallaxElements.forEach((el) => {
        const speed = parseFloat(el.dataset.parallax) || 0.1;
        const y = scrolled * speed;
        el.style.transform = `translateY(${y}px)`;
      });
    };

    let ticking = false;
    window.addEventListener("scroll", () => {
      if (!ticking) {
        window.requestAnimationFrame(() => {
          handleScroll();
          ticking = false;
        });
        ticking = true;
      }
    });
  }
});
