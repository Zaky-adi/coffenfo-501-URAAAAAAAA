/* ===== 1. Variabel & dasar (dipakai semua halaman pengguna) ===== */
:root{--ink:#3b2620;--cream:#faf6ef;--card:#fff;--line:#eae2d3;--muted:#8a7f74;--accent:#cd7f3a;--tint:#fbeedd;--gold:#e8a317}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:var(--cream);color:var(--ink);font-size:13px}
a{color:inherit;text-decoration:none}
button{font-family:inherit;cursor:pointer}
.icon{width:1em;height:1em;flex:none}
.wrap{max-width:1100px;margin:0 auto;padding:0 28px}

/* ===== 2. Navbar ===== */
.nav{background:#fff;border-bottom:1px solid var(--line);position:sticky;top:0;z-index:10}
.nav .wrap{display:flex;align-items:center;gap:24px;height:62px}
.logo{display:flex;align-items:center;gap:8px;font-weight:700;font-size:16px}
.logo img{width:24px;height:24px;object-fit:contain}
.search{flex:1;max-width:480px;margin:0 auto;position:relative}
.search .icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:14px}
.search input{width:100%;height:36px;border:0;border-radius:99px;background:#f4ede2;padding:0 16px 0 38px;font:12px 'Plus Jakarta Sans',sans-serif;color:var(--ink)}
.search input:focus{outline:2px solid var(--ink)}
.loc{display:flex;align-items:center;gap:6px;border:1px solid var(--line);background:#fff;border-radius:99px;padding:7px 12px;font-size:11px;font-weight:600}
.avatar{width:30px;height:30px;border-radius:50%;background:var(--ink);color:#fff;font-size:10px;font-weight:600;display:grid;place-items:center}

/* ===== 3. Judul bagian ===== */
.page{padding:28px 0 60px}
.sec{margin-top:34px}
.sec-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:16px}
.sec h2{font-family:'Lora',Georgia,serif;font-size:19px;font-weight:600}
.sec p.sub{font-size:11px;color:var(--muted);margin-top:3px}
.more{display:flex;align-items:center;gap:3px;font-size:11px;font-weight:600;color:var(--accent)}

/* ===== 4. Kartu filter ===== */
.filter{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:20px 22px}
.f-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
.f-head strong{font-size:14px}
.link{border:0;background:none;color:var(--accent);font-size:11px;font-weight:600}
.f-block+.f-block{margin-top:16px;padding-top:14px;border-top:1px solid var(--line)}
.f-title{font-size:10px;font-weight:700;letter-spacing:.04em;color:var(--accent);margin-bottom:10px}
.f-cols{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.f-group>span{display:block;font-size:10.5px;color:var(--muted);margin-bottom:8px}
.chips{display:flex;flex-wrap:wrap;gap:7px}
.chip{position:relative}
.chip input{position:absolute;opacity:0;pointer-events:none}
.chip span{display:inline-block;border:1px solid var(--line);border-radius:99px;padding:6px 13px;font-size:11px;background:#fff;cursor:pointer;transition:.15s}
.chip span:hover{border-color:#cfc3ad}
.chip input:checked + span{background:var(--ink);border-color:var(--ink);color:#fff}
.chip input:focus-visible + span{outline:2px solid var(--accent);outline-offset:2px}

/* saklar self-brewing */
.switch{display:inline-flex;align-items:center;gap:10px;font-size:11px;cursor:pointer}
.switch input{position:absolute;opacity:0}
.switch i{width:34px;height:20px;border-radius:99px;background:#e1d8c8;position:relative;transition:.15s}
.switch i::after{content:"";position:absolute;top:3px;left:3px;width:14px;height:14px;border-radius:50%;background:#fff;transition:.15s}
.switch input:checked + i{background:var(--accent)}
.switch input:checked + i::after{left:17px}

/* ===== 5. Kartu cafe ===== */
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.cafe{background:var(--card);border:1px solid var(--line);border-radius:14px;overflow:hidden;transition:box-shadow .15s,transform .15s}
.cafe:hover{box-shadow:0 8px 24px rgba(59,38,32,.1);transform:translateY(-2px)}
.thumb{position:relative;aspect-ratio:16/10;display:grid;place-items:center;color:rgba(255,255,255,.8);font-size:40px}
.thumb img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.open{position:absolute;left:10px;bottom:10px;display:flex;align-items:center;gap:5px;background:#fff;border-radius:99px;padding:4px 10px;font-size:10px;font-weight:600;color:var(--ink)}
.open::before{content:"";width:6px;height:6px;border-radius:50%;background:#14b87a}
.fav{position:absolute;right:10px;top:10px;width:28px;height:28px;border:0;border-radius:50%;background:#fff;color:var(--ink);display:grid;place-items:center;font-size:14px}
.fav.on{color:#e03a3a}.fav.on svg{fill:currentColor}
.info{padding:14px 16px 14px}
.r1{display:flex;justify-content:space-between;gap:8px;align-items:flex-start}
.info h3{font-size:13px;font-weight:700;line-height:1.3}
.rate{display:flex;align-items:center;gap:3px;background:var(--tint);color:#8a5a22;border-radius:6px;padding:3px 7px;font-size:10px;font-weight:700;white-space:nowrap}
.rate .icon{color:var(--gold)}
.rate small{font-weight:500;font-size:9px}
.addr{display:flex;gap:5px;font-size:10.5px;color:var(--muted);margin:7px 0 10px;line-height:1.4}
.addr .icon{margin-top:1px}
.tags{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:12px}
.tags span{background:var(--tint);color:#8a5a22;border-radius:5px;padding:3px 8px;font-size:9.5px;font-weight:600}
.foot{display:flex;justify-content:space-between;align-items:center;padding-top:10px;border-top:1px solid var(--line);font-size:10.5px;color:var(--muted)}
.feat{display:flex;gap:10px;font-size:14px;color:var(--accent)}
.foot b{color:var(--ink)}

/* ===== 6. Responsif ===== */
@media(max-width:900px){.grid{grid-template-columns:repeat(2,1fr)}.f-cols{grid-template-columns:1fr 1fr}.loc{display:none}}
@media(max-width:560px){.grid,.f-cols{grid-template-columns:1fr}.wrap{padding:0 16px}}

/* ===== 7. Halaman detail cafe ===== */
html{scroll-behavior:smooth}
:root{--band:#f1f1d3;--sand:#f5efe5}
.ph{position:relative;display:grid;place-items:center;color:rgba(255,255,255,.8);font-size:38px;overflow:hidden;background-size:cover}
.ph.has>svg.cup{display:none}
.ph img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}

/* header hijau */
.hbar{background:var(--band);padding-bottom:14px}
.h-row{display:flex;justify-content:space-between;align-items:center;height:64px}
.h-row nav{display:flex;align-items:center;gap:26px;font-size:12px;color:#5b4d42}
.h-row nav a.on{font-weight:700;color:var(--ink)}
.loc2{display:flex;align-items:center;gap:5px;color:var(--ink)}
.fine{display:flex;justify-content:space-between;font-size:9.5px;color:#6b5f54;margin-top:10px}
.fine .r{display:flex;gap:22px}
.fine+.fine{margin-top:12px}

.crumb{display:flex;align-items:center;gap:7px;font-size:10.5px;color:var(--muted);margin-bottom:14px}
.crumb b{color:var(--ink);font-weight:600}

/* galeri */
.gallery{position:relative;display:grid;grid-template-columns:2.7fr 1fr;grid-template-rows:1fr 1fr;gap:8px;height:300px;border-radius:12px;overflow:hidden}
.gallery .ph:first-child{grid-row:span 2}
.g-count{position:absolute;left:14px;bottom:12px;display:flex;align-items:center;gap:10px;font-size:9px;color:#fff;font-weight:600;text-shadow:0 1px 3px rgba(0,0,0,.5)}
.dots{display:flex;gap:4px}.dots i{width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.5)}.dots i.on{background:#fff}
.g-act{position:absolute;top:12px;right:12px;display:flex;gap:8px}
.chipbtn{display:inline-flex;align-items:center;gap:6px;border:0;background:#fff;border-radius:8px;padding:7px 12px;font-size:10px;font-weight:600;color:var(--ink);box-shadow:0 2px 8px rgba(0,0,0,.12)}
.chipbtn.on{color:var(--accent)}.chipbtn.on svg{fill:currentColor}
.g-nav{position:absolute;right:12px;bottom:12px;display:flex;gap:8px}
.round{width:30px;height:30px;border-radius:50%;border:1px solid var(--line);background:#fff;display:grid;place-items:center;font-size:13px;color:var(--ink)}

/* judul */
.d-head{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin:22px 0 10px}
.eyebrow{font-size:8.5px;font-weight:600;letter-spacing:.06em;color:var(--muted)}
.d-head h1{font-family:'Lora',Georgia,serif;font-size:28px;font-weight:500;margin:8px 0 12px}
.d-act{display:flex;align-items:center;gap:10px;margin-top:6px}
.status{display:flex;align-items:center;gap:6px;background:#e4efdc;color:#2f6b3f;border-radius:99px;padding:7px 13px;font-size:10px;font-weight:600}
.status::before{content:"";width:6px;height:6px;border-radius:50%;background:#2f9e5b}
.btn-o{display:inline-flex;align-items:center;gap:6px;border:1px solid var(--line);background:#fff;border-radius:99px;padding:7px 14px;font-size:10.5px;font-weight:600}
.meta{display:flex;flex-wrap:wrap;gap:14px;align-items:center;font-size:10.5px;color:var(--muted)}
.meta .score{display:inline-flex;align-items:center;gap:5px;background:#f0e2c8;color:#6b4a22;border-radius:6px;padding:4px 9px;font-weight:700}
.meta b{color:var(--ink)}
.meta span.p{display:inline-flex;align-items:center;gap:4px}
.desc{font-size:11.5px;color:#5b4d42;margin-bottom:20px}
.rule{border:0;border-top:1px solid var(--line);margin-bottom:24px}

/* dua kolom */
.detail{display:grid;grid-template-columns:minmax(0,1fr) 270px;gap:28px;align-items:start}
.know{background:var(--band);border:1px solid #e4e4bf;border-radius:12px;padding:18px 22px;font-size:11px}
.know h3{display:flex;align-items:center;gap:8px;font-size:12.5px;margin-bottom:14px}
.know dl{display:grid;grid-template-columns:120px 1fr;gap:9px 10px}
.know dt{color:var(--muted)}.know dd{font-weight:700}
.know p{margin-top:12px;color:var(--muted)}
.tabs-d{display:grid;grid-template-columns:repeat(3,1fr);gap:4px;background:#f1ebe0;border-radius:10px;padding:4px;margin:20px 0 26px}
.tabs-d a{text-align:center;padding:9px;border-radius:7px;font-size:11px;font-weight:600;color:var(--muted)}
.tabs-d a.on{background:#fff;color:var(--ink);box-shadow:0 1px 3px rgba(59,38,32,.1)}
.tabs-d a.on::before{content:"+ ";color:var(--accent)}
.d-sec{margin-bottom:34px;scroll-margin-top:20px}
.serif{font-family:'Lora',Georgia,serif;font-weight:500}
.d-sec h2.serif{font-size:20px}
.d-sec>.sub{font-size:10.5px;color:var(--muted);margin:3px 0 14px}
.pills{display:flex;gap:7px;margin-bottom:16px}
.pill{border:1px solid var(--line);background:#fff;border-radius:99px;padding:5px 13px;font-size:10px;color:var(--ink)}
.pill.on{background:var(--ink);border-color:var(--ink);color:#fff}

/* menu */
.menu-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.menu-card{background:#fff;border:1px solid var(--line);border-radius:10px;overflow:hidden}
.menu-card[hidden]{display:none}
.menu-card .ph{aspect-ratio:16/10}
.menu-card .t{padding:10px 12px 12px}
.menu-card strong{display:block;font-size:10.5px}
.menu-card small{display:block;font-size:8.5px;color:var(--muted);margin:3px 0 7px}
.menu-card b{font-size:10.5px}
.note-s{font-size:9px;color:var(--muted);margin-top:14px}

/* sidebar */
.aside{display:grid;gap:22px}
.box{background:#fff;border:1px solid var(--line);border-radius:12px;padding:16px}
.box h3{font-size:15px;margin-bottom:12px}
.map{position:relative;height:118px;border-radius:8px;overflow:hidden;margin-bottom:12px;background:#eef0e0}
.map svg{width:100%;height:100%;display:block}
.zoom{position:absolute;right:8px;top:8px;display:grid;background:#fff;border-radius:6px;overflow:hidden;font-size:11px;text-align:center;box-shadow:0 1px 4px rgba(0,0,0,.15)}
.zoom span{padding:3px 8px}.zoom span+span{border-top:1px solid var(--line)}
.box strong{font-size:11px}
.addr-d{font-size:9.5px;color:#5b4d42;margin:4px 0 12px}
.btn-dark{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;border-radius:8px;background:var(--ink);color:#fff;padding:10px;font-size:10.5px;font-weight:600}
.btn-dark:hover{background:#523830}
.hours{display:flex;gap:9px;margin-top:14px;padding-top:12px;border-top:1px solid var(--line);font-size:10px}
.hours small{display:block;color:var(--muted);margin-top:2px}
.fac-t{font-size:17px;margin:2px 0 -8px}
.fac{display:grid;grid-template-columns:1fr 1fr;gap:12px 10px;font-size:10px}
.fac div{display:flex;align-items:center;gap:8px}
.fac .ic{width:26px;height:26px;border-radius:7px;background:#f4ede2;display:grid;place-items:center;font-size:13px;color:#5b4d42;flex:none}

/* ulasan */
.rv-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
.rv-top a{font-size:10.5px;font-weight:700;text-decoration:underline}
.rv-sum{display:flex;align-items:center;gap:14px;background:var(--sand);border-radius:10px;padding:14px 18px;margin-bottom:6px}
.big{font-family:'Lora',serif;font-size:32px;line-height:1}
.stars{color:#d9b27a;letter-spacing:2px;font-size:12px}
.rv-sum small{display:block;font-size:9px;color:var(--muted);margin-top:3px}
.rv{display:grid;grid-template-columns:34px 1fr auto;gap:0 10px;padding:14px 0;border-bottom:1px solid var(--line)}
.rv .av{width:30px;height:30px;border-radius:50%;background:#f0e2c8;font-size:9px;font-weight:700;display:grid;place-items:center;color:#6b4a22}
.rv strong{font-size:10.5px;display:block}.rv small{font-size:8.5px;color:var(--muted)}
.rv .sc{font-size:10px;font-weight:700}
.rv p{grid-column:1/-1;font-size:10.5px;color:#5b4d42;margin-top:10px}
.all{display:inline-block;margin-top:14px;font-size:10.5px;font-weight:700;text-decoration:underline}

/* cafe serupa */
.sim-head{display:flex;justify-content:space-between;align-items:flex-end;margin:34px 0 16px;padding-top:26px;border-top:1px solid var(--line)}
.sim-head h2{font-size:23px}.sim-head p{font-size:10.5px;color:var(--muted);margin-top:4px}
.sim-head .arrows{display:flex;gap:8px}
.sim{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.sim-card{background:#fff;border:1px solid var(--line);border-radius:12px;overflow:hidden}
.sim-card .ph{aspect-ratio:16/8}
.sim-card .bm{position:absolute;right:10px;top:10px;width:24px;height:24px;border-radius:6px;background:#fff;display:grid;place-items:center;color:var(--ink);font-size:12px}
.sim-card .t{padding:12px 14px 14px}
.sim-card .r1{display:flex;justify-content:space-between;font-size:11px}
.sim-card small{display:block;font-size:9.5px;color:var(--muted);margin:3px 0 9px}
.sim-card em{font-style:normal;background:#f4ede2;border-radius:5px;padding:3px 8px;font-size:8.5px;color:#5b4d42}

@media(max-width:900px){.detail{grid-template-columns:1fr}.menu-grid,.sim{grid-template-columns:1fr 1fr}.gallery{height:220px}.fine{flex-direction:column;gap:6px}}
@media(max-width:560px){.menu-grid,.sim{grid-template-columns:1fr}.d-head{flex-direction:column}.know dl{grid-template-columns:1fr}.h-row nav a:not(.on){display:none}}