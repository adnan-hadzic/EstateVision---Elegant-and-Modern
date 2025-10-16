(function(){
  const root = document.getElementById('spapp');
  function hideAll(){ root.querySelectorAll('section').forEach(s=>s.style.display='none'); }
  function show(id){
    const el = root.querySelector('#'+CSS.escape(id));
    if(!el){ return; }
    const url = el.getAttribute('data-load');
    if(url){
      fetch(url).then(r=>r.text()).then(html=>{
        el.innerHTML = html;
        el.dataset.loaded = '1';
        el.style.display = '';
        bind(id);
      });
    } else { el.style.display = ''; bind(id); }
    window.scrollTo({top:0, behavior:'instant'});
  }
  function route(){
  hideAll();
  let hash = location.hash.replace('#','');
  if(!hash) { hash = 'home'; location.hash = '#home'; } 
  show(hash);
}
  function bind(id){
    if(id==='login'){ const f=document.getElementById('loginForm'); f&&f.addEventListener('submit', e=>{e.preventDefault(); alert('Milestone 1: static login form.');}); }
    if(id==='register'){ const f=document.getElementById('registerForm'); f&&f.addEventListener('submit', e=>{e.preventDefault(); alert('Milestone 1: static register form.');}); }
  }
  window.addEventListener('hashchange', route);
  window.addEventListener('load', route);
})();