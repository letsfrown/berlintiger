window.addEventListener('load', function() {
  // Function to update scroll-margin-top
  function updateScrollMarginTop() {
      // Get the height of the navigation bar
      var headerHeight = document.querySelector('header').offsetHeight;
      // Set the scroll-margin-top property on the header, sections, and asides
      var elementsToUpdate = document.querySelectorAll('header, section, aside');
      elementsToUpdate.forEach(function(element) {
          element.style.scrollMarginTop = headerHeight + 'px';
      });
  }

  // Call the function on page load
  updateScrollMarginTop();

  // Call the function whenever the window is resized
  window.addEventListener('resize', updateScrollMarginTop);
});

document.querySelectorAll('.video-container[data-youtube-id]').forEach(function(container) {
  var poster = container.querySelector('.video-poster');
  if (!poster) return;

  poster.addEventListener('click', function() {
    var id = container.getAttribute('data-youtube-id');
    if (!id) return;

    var iframe = document.createElement('iframe');
    iframe.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0&modestbranding=1';
    iframe.title = poster.getAttribute('aria-label') || 'YouTube video';
    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowFullscreen = true;
    iframe.setAttribute('frameborder', '0');

    container.innerHTML = '';
    container.appendChild(iframe);
  });
});






// Get all elements with class "dot"
const dots = document.querySelectorAll('.dot');

// Function to handle tap event
const handleTap = (event) => {
  // Toggle the "hover" class on the tapped element
  event.target.classList.toggle('hover');
  // Remove the "hover" class from other elements
  dots.forEach(dot => {
    if(dot !== event.target) {
      dot.classList.remove('hover');
    }
  });
};

// Add tap event listener to each dot
dots.forEach(dot => {
  dot.addEventListener('touchstart', handleTap);
});

// Add tap event listener to document to remove hover effect
document.addEventListener('touchstart', (event) => {
  if (!event.target.classList.contains('dot')) {
    dots.forEach(dot => {
      dot.classList.remove('hover');
    });
  }
});
