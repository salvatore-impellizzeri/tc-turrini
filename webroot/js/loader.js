class ObserverAnimation {
  constructor(el) {
    // Elements
    this.$el = el;
    this.isInView = true;
    this.positionClass = "is-out is-out--down";
    this.observer = null;

    this.currClass = this.$el.className;

    this.attachEvents();
  }

  visibilityChanged(isVisible, entry) {
    this.isInView = isVisible;

    if (this.isInView & (entry.intersectionRatio > 0)) {
      this.positionClass = "is-in";
    } else {
      if (entry.boundingClientRect.y < 0) {
        this.positionClass = "is-out is-out--up";
      } else {
        this.positionClass = "is-out is-out--down";
      }
    }

    return this.positionClass;
  }

  observerScroll() {
    // IntersectionObserver Supported
    let options = {};

    options = {
      root: null,
      rootMargin: "0px",
      threshold: 0.1,
    };

    // Create new IntersectionObserver
    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        let posClass = this.visibilityChanged(entry.isIntersecting, entry);

        this.$el.className = this.currClass + " " + posClass;
      });
    }, options);

    // Start observing
    observer.observe(this.$el);
  }

  attachEvents() {
    this.observerScroll();
  }
}

var initObserver = function () {
  boxElements = document.querySelectorAll("[data-animated]");

  boxElements.forEach((el, i) => {
    new ObserverAnimation(el);
  });
};

var initVideoObserver = function () {
  if (!!window.IntersectionObserver) {
    let videos = document.querySelectorAll("[data-play-video]");

    if (videos) {
      let observer = new IntersectionObserver((entries, observer) => {
        entries.map((entry) => {
          if (entry.isIntersecting) {
            entry.target.play();
          } else {
            entry.target.pause();
          }
        });
      });

      videos.forEach((video) => {
        observer.observe(video);
      });
    }
  }
};

var initScroll = function () {
  //classe aggiuntiva scroll
  $("body").toggleClass("body--scroll", $(document).scrollTop() > 0);
  $(document).on("scroll", () => {
    $("body").toggleClass("body--scroll", $(document).scrollTop() > 0);
  });
};

var start = new Date().getTime(),
  boxElements = null,
  observer = null;
loadSite = function () {
  document.querySelector("body").classList.add("loading-done");
  initObserver();
  initVideoObserver();
};

window.addEventListener("DOMContentLoaded", (event) => {
  initScroll();
});

window.addEventListener("load", (event) => {

  if (new Date().getTime() - start < 1000) {
    window.setTimeout(loadSite, 1000 - (new Date().getTime() - start));
  } else {
    loadSite();
  }
});
