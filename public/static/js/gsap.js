gsap.registerPlugin(ScrollTrigger);
gsap.timeline({
  delay: 0,
  scrollTrigger: {
    trigger: "#tips",
    start: "top 80%",
    end: "bottom 0%",
    toggleActions: "play reset play reset ",
  },
})
  .fromTo("#tip1", { delay: 0.5, y: 300, opacity: 0 }, { opacity: 1, y: 0 })
  .fromTo("#tip2", { y: 300, delay: 0.5, opacity: 0 }, { opacity: 1, y: 0 })
  .fromTo("#tip3", { delay: 0.5, y: 300, opacity: 0 }, { opacity: 1, y: 0 });

function hoverEnter(obj) {
  gsap.to(obj.target, { duration: 0.2, scale: 1.05 });
}
function hoverLeave(obj) {
  gsap.to(obj.target, { duration: 0.2, scale: 1 });
}

gsap.fromTo(
  "#portfolio-1",
  { y: 0, x: -10, opacity: 0 },
  {
    opacity: 1,
    x: 0,
    scrollTrigger: {
      trigger: "#portfolio-header",
      start: "top 90%",
      end: "bottom 95%",
      toggleActions: "play stop play reset ",
    },
  }
);
gsap.fromTo(
  "#portfolio-2",
  { y: 0, x: 10, opacity: 0 },
  {
    opacity: 1,
    x: 0,
    scrollTrigger: {
      trigger: "#portfolio-header",
      start: "top 90%",
      end: "bottom 95%",
      toggleActions: "play stop play reset ",
    },
  }
);

document.querySelectorAll(".timeline__event_animation")
  .forEach((timelineEvent, index) => {
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: timelineEvent,
        start: "top 90%",
        end: "bottom 0%",
        toggleActions: "play reverse play reverse",
      },
    });

    if (index % 2 === 0) {
      tl.fromTo(
        timelineEvent,
        { x: "-100%", opacity: 0 },
        { x: "0%", opacity: 1, duration: 1 }
      );
    } else {
      tl.fromTo(
        timelineEvent,
        { x: "100%", opacity: 0 },
        { x: "0%", opacity: 1, duration: 1 }
      );
    }
  });
