(()=>{
const $=s=>document.querySelector(s), $$=s=>[...document.querySelectorAll(s)];
/* ---- idioma ---- */
const ES={};$$('[data-i18n]').forEach(e=>ES[e.dataset.i18n]=e.textContent);
Object.assign(ES,ES_EXTRA);
let lang=localStorage.getItem('lang')||((navigator.language||'es').startsWith('en')?'en':'es');
const t=k=>(lang==='en'?EN:ES)[k]||ES[k]||k;
function setLang(l){lang=l;localStorage.setItem('lang',l);document.documentElement.lang=l;
 $$('[data-i18n]').forEach(e=>e.textContent=t(e.dataset.i18n));
 $('#langBtn').textContent=l==='en'?'ES':'EN';}
$('#langBtn').onclick=()=>setLang(lang==='en'?'es':'en');
setLang(lang);
$('#yr').textContent=new Date().getFullYear();

/* ---- menú móvil ---- */
const menu=$('#menu'),burger=$('#burger');
burger.onclick=()=>{menu.classList.toggle('open');burger.classList.toggle('open')};
menu.addEventListener('click',e=>{if(e.target.tagName==='A'){menu.classList.remove('open');burger.classList.remove('open')}});

/* ---- scroll: reveal ligero + botón arriba + link activo ---- */
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}}),{threshold:.12});
$$('.reveal').forEach(e=>io.observe(e));
const top=$('#top');let tick=false;
addEventListener('scroll',()=>{if(tick)return;tick=true;requestAnimationFrame(()=>{top.classList.toggle('show',scrollY>500);tick=false})},{passive:true});
top.onclick=()=>scrollTo({top:0,behavior:'smooth'});
const links=$$('#menu a'),so=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting)links.forEach(a=>a.classList.toggle('on',a.getAttribute('href')==='#'+e.target.id))}),{rootMargin:'-45% 0px -50% 0px'});
$$('main section').forEach(s=>so.observe(s));

/* ---- formulario (php/contact.php, envío SMTP) ---- */
const f=$('#form'),m=$('#msg');
f.addEventListener('submit',async e=>{e.preventDefault();m.className='msg';
 if(!f.checkValidity()){m.textContent=t('err');m.classList.add('err');return}
 m.textContent=t('sending');
 try{const r=await fetch('php/contact.php',{method:'POST',body:new FormData(f)});const j=await r.json();
  if(j.ok){m.textContent=t('ok');m.classList.add('ok');f.reset()}else throw 0}
 catch{m.textContent=t('err');m.classList.add('err')}});
})();
