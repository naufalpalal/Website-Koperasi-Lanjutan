
    document.addEventListener('DOMContentLoaded', function(){
      const input = document.getElementById('searchInput');
      if(!input) return;
      input.addEventListener('input', function(){
        const q = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#tableBody tr');
        rows.forEach(tr=>{
          const nameCell = tr.querySelector('td');
          const name = nameCell ? nameCell.textContent.toLowerCase() : '';
          tr.style.display = q && !name.includes(q) ? 'none' : '';
        });
        const cards = document.querySelectorAll('#mobileList > div');
        cards.forEach(card=>{
          const nameEl = card.querySelector('.font-semibold');
          const name = nameEl ? nameEl.textContent.toLowerCase() : '';
          card.style.display = q && !name.includes(q) ? 'none' : '';
        });
      });
    });
