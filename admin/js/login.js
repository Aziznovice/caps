// script.js

let isDragging = false;
let initialX;
let initialY;
let offsetX = 0;
let offsetY = 0;

const row = document.querySelector('.row');
const container = document.querySelector('.container'); // Assuming you have a container element

row.addEventListener('mousedown', (e) => {
  isDragging = true;
  initialX = e.clientX - offsetX;
  initialY = e.clientY - offsetY;
  row.style.cursor = 'grabbing'; // Change cursor style while dragging
});

document.addEventListener('mousemove', (e) => {
  if (!isDragging) return;

  const currentX = e.clientX - initialX;
  const currentY = e.clientY - initialY;

  const centerX = container.clientWidth / 2;
  const centerY = container.clientHeight / 2;

  offsetX = currentX;
  offsetY = currentY;

  row.style.transform = `translate(${currentX}px, ${currentY}px) translate(-50%, -50%)`; // Adjust for centering
});

document.addEventListener('mouseup', () => {
  isDragging = false;
  row.style.cursor = 'grab'; // Restore grab cursor after releasing the mouse button
});
