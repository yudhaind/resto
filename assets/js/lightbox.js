// JavaScript Document
const lightbox = document.getElementById('lightbox');

function openLightbox(){
    lightbox.classList.add('active');
    document.body.style.overflow='hidden';
}

function closeLightbox(){
    lightbox.classList.remove('active');
    document.body.style.overflow='auto';
}

lightbox.addEventListener('click', function(e){
    if(e.target === this){
        closeLightbox();
    }
});

document.addEventListener('keydown', function(e){
    if(e.key === "Escape"){
        closeLightbox();
    }
});

