</main></div><script>
function filterBySustancia(){const s=document.getElementById('sustancia_id'); if(!s)return; const tipo=s.options[s.selectedIndex]?.dataset.tipo||''; document.querySelectorAll('[data-sustancia]').forEach(el=>{el.style.display=(el.dataset.sustancia===s.value)?'':'none';}); document.querySelectorAll('[data-tipo-muestra]').forEach(opt=>{const allowed=opt.dataset.tipoMuestra; opt.disabled=!(allowed==='MIXTA'||allowed===tipo);});}
document.addEventListener('DOMContentLoaded',filterBySustancia);
</script></body></html>
