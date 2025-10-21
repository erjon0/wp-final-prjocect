// Simple filter functionality for demo filter-bar
(function(){
  document.addEventListener('DOMContentLoaded', function(){
    const buttons = document.querySelectorAll('.filter-bar button');
    const cards = document.querySelectorAll('.business-card');
    buttons.forEach(btn => {
      btn.addEventListener('click', function(){
        buttons.forEach(b=>b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.getAttribute('data-filter');
        cards.forEach(card=>{
          if(filter === '*' || card.getAttribute('data-category') === filter){
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  });
})();
