<?php
$id_Rol      = $_SESSION['id_Rol']      ?? null;
$id_Paciente = $_SESSION['id_Paciente'] ?? null;
$id_Empleado = $_SESSION['id_Empleado'] ?? null;

$turnos_json     = json_encode($turnos_mes      ?? []);
$bloqueados_json = json_encode($dias_bloqueados ?? []);

$servicios_arr  = $servicios_db  ?? [];
$terapeutas_arr = $terapeutas_db ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Turnos - SoftAgenda</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --pink: #e879a0;
    --pink-light: #f5b8d0;
    --pink-pale: #fde8f0;
    --red: #e05555;
    --red-pale: #fdeaea;
    --cream: #f7c6d0;
    --dark: #1a1a1a;
    --gray: #888;
    --gray-pale: #f5f5f5;
    --gray-mid: #ccc;
    --green: #5a9e6f;
    --green-pale: #eaf4ee;
    --status-success-bg: #e9edc9;
    --status-success-text: #4e5a37;
    --status-warning-bg: #fff3cd;
    --status-warning-text: #856404;
    --agenda-bg: #fdfbf7;
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body {
    background: var(--cream);
    min-height: 100vh;
    font-family: 'DM Sans', sans-serif;
    padding: 20px;
    color: var(--dark);
    transition: background 0.3s, color 0.3s;
  }

  /* ── LAYOUT GENERAL ── */
  .page-wrapper {
    display: flex;
    gap: 24px;
    max-width: 860px;
    margin: 0 auto;
    align-items: flex-start;
    flex-wrap: wrap;
  }

  /* ── LAYOUT ADMIN ── */
  body.vista-admin {
    background: linear-gradient(165deg, #F9DADE 0%, #F0B3BB 100%) fixed;
  }
  .admin-wrapper {
    --cherry: #F0B3BB;
    --blush: #F9DADE;
    --porcelain: #FAF8F2;
    --mint: #D3E8D8;
    --mint-deep: #4f8a65;
    --rose-deep: #B85670;
    --ink: #4a2f36;
    --muted: #9a7f86;
    max-width: 920px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 22px;
  }
  .admin-header { text-align: center; padding: 10px 0 0; }
  .admin-header .title-turnos {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem; font-weight: 900;
    color: var(--ink); letter-spacing: -1px; line-height: 1;
  }
  .admin-header .title-month {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem; font-weight: 900;
    color: var(--rose-deep); letter-spacing: -1px;
  }
  .sparkle {
    display: inline-block; font-size: 1.1rem; color: var(--pink);
    animation: sparkle 2s ease-in-out infinite; margin-left: 4px; vertical-align: top;
  }
  .admin-header .sparkle { color: #A9D4B5; text-shadow: 0 1px 8px rgba(79,138,101,0.35); }
  @keyframes sparkle {
    0%,100%{opacity:1;transform:scale(1) rotate(0deg);}
    50%{opacity:0.6;transform:scale(1.3) rotate(20deg);}
  }

  .admin-wrapper .back-btn { background: var(--porcelain); color: var(--rose-deep); box-shadow: 0 6px 18px rgba(184,86,112,0.15); }
  .admin-wrapper .back-btn:hover { background: var(--blush); }

  /* ── TARJETA CON PESTAÑAS ── */
  .admin-card {
    background: var(--porcelain);
    border-radius: 32px;
    padding: 22px 24px 20px;
    box-shadow: 0 18px 50px rgba(184,86,112,0.20);
    transition: background 0.3s;
  }
  .admin-tabs {
    display: flex; gap: 6px;
    background: var(--blush);
    padding: 6px;
    border-radius: 999px;
  }
  .admin-tab {
    flex: 1;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    border: none; background: transparent; cursor: pointer;
    font-family: 'DM Sans', sans-serif; font-size: 0.9rem; font-weight: 500;
    color: var(--rose-deep);
    padding: 12px 14px;
    border-radius: 999px;
    transition: background 0.25s, color 0.25s, box-shadow 0.25s;
  }
  .admin-tab:hover:not(.active) { background: rgba(255,255,255,0.45); }
  .admin-tab.active {
    background: var(--porcelain);
    color: var(--ink);
    box-shadow: 0 4px 14px rgba(184,86,112,0.20);
  }
  .admin-tab .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
  .dot-pendientes { background: var(--cherry); }
  .dot-realizadas { background: #9CCBA9; }
  .dot-canceladas { background: #D99A9A; }
  .admin-tab .count {
    font-size: 0.72rem; font-weight: 500;
    padding: 2px 9px; border-radius: 999px;
    background: rgba(255,255,255,0.65);
    color: var(--rose-deep);
  }
  .admin-tab.active .count-pendientes { background: var(--blush); color: var(--rose-deep); }
  .admin-tab.active .count-realizadas { background: var(--mint);  color: var(--mint-deep); }
  .admin-tab.active .count-canceladas { background: #F4E1E1;      color: #B36A6A; }

  .tab-panel { display: none; }
  .tab-panel.active { display: block; animation: tabIn 0.35s ease; }
  @keyframes tabIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: none; }
  }

  /* Encabezado del panel + buscador */
  .panel-heading {
    padding: 22px 6px 6px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
  }
  .panel-heading h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.35rem; font-weight: 700; color: var(--ink);
  }
  .panel-heading p { font-size: 0.82rem; color: var(--muted); margin-top: 2px; }

  .buscador-turnos {
    width: 280px; max-width: 100%;
    padding: 10px 16px;
    border: 1.5px solid var(--blush);
    border-radius: 999px;
    background: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem; color: var(--ink);
    outline: none;
    transition: border 0.2s, box-shadow 0.2s;
  }
  .buscador-turnos:focus {
    border-color: var(--rose-deep);
    box-shadow: 0 0 0 3px rgba(184,86,112,0.15);
  }
  .sin-resultados { display: none; }

  /* ── TABLAS ADMIN ── */
  .table-scroll { max-height: 55vh; overflow: auto; margin-top: 6px; padding-right: 4px; }
  .admin-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
    font-size: 0.86rem;
    min-width: 560px;
  }
  .admin-table thead th {
    position: sticky; top: 0; z-index: 1;
    background: var(--porcelain);
    text-align: left;
    font-size: 0.66rem; font-weight: 500;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: var(--rose-deep); padding: 6px 14px;
  }
  .admin-table tbody td {
    background: #fff; color: var(--ink);
    padding: 13px 14px; vertical-align: middle;
    transition: background 0.15s;
  }
  .admin-table tbody td:first-child { border-radius: 16px 0 0 16px; }
  .admin-table tbody td:last-child  { border-radius: 0 16px 16px 0; }
  .admin-table tbody tr:hover td { background: #FDEFF1; }
  .td-fecha    { color: var(--muted); font-size: 0.8rem; white-space: nowrap; }
  .td-nombre   { font-weight: 500; }
  .td-servicio { color: var(--muted); font-size: 0.82rem; }
  .hora-pill {
    display: inline-block; padding: 4px 11px; border-radius: 999px;
    font-size: 0.8rem; font-weight: 500; white-space: nowrap;
  }
  .hora-pendientes { background: var(--blush); color: var(--rose-deep); }
  .hora-realizadas { background: var(--mint);  color: var(--mint-deep); }
  .hora-canceladas { background: #F4E1E1;      color: #B36A6A; }
  .empty-state { text-align: center; padding: 48px 0 36px; color: var(--muted); font-size: 0.9rem; }
  .empty-state .spark { display: block; font-size: 1.8rem; color: var(--cherry); margin-bottom: 8px; }

  @media (max-width: 700px) {
    .admin-card { padding: 14px; border-radius: 26px; }
    .admin-tab { padding: 10px 6px; font-size: 0.78rem; gap: 5px; }
    .admin-tab .dot { display: none; }
    .table-scroll { max-height: 60vh; }
    .buscador-turnos { width: 100%; }
  }

  /* ── CALENDARIO ── */
  .card {
    background: #fff;
    border-radius: 32px;
    padding: 32px 28px 28px;
    width: 360px;
    flex-shrink: 0;
    box-shadow: 0 8px 40px rgba(232,121,160,0.12);
    position: relative;
    overflow: hidden;
    transition: background 0.3s;
  }
  .card::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:180px; height:180px;
    background:radial-gradient(circle, var(--pink-pale) 0%, transparent 70%);
    border-radius:50%;
  }
  .header { text-align:center; margin-bottom:24px; position:relative; }
  .title-turnos {
    font-family:'Playfair Display',serif;
    font-size:2.2rem; font-weight:900;
    color:var(--dark); letter-spacing:-1px; line-height:1;
  }
  .title-month {
    font-family:'Playfair Display',serif;
    font-size:2.2rem; font-weight:900;
    color:var(--pink); letter-spacing:-1px; line-height:1.1;
  }
  .nav {
    display:flex; align-items:center;
    justify-content:space-between; margin-bottom:16px;
  }
  .nav-btn {
    background:none; border:none; cursor:pointer; color:var(--pink);
    font-size:1.2rem; width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center; transition:background 0.2s;
  }
  .nav-btn:hover { background:var(--pink-pale); }
  .nav-label {
    font-family:'Playfair Display',serif; font-size:1rem;
    font-weight:700; color:var(--gray); letter-spacing:1px; text-transform:uppercase;
  }
  .weekdays { display:grid; grid-template-columns:repeat(7,1fr); margin-bottom:6px; }
  .weekday {
    text-align:center; font-size:0.7rem; font-weight:500;
    color:var(--gray); letter-spacing:0.5px; padding:5px 0; text-transform:uppercase;
  }
  .days { display:grid; grid-template-columns:repeat(7,1fr); gap:3px; }
  .day {
    aspect-ratio:1; display:flex; align-items:center; justify-content:center;
    border-radius:50%; font-size:0.85rem; color:var(--dark);
    cursor:pointer; transition:all 0.18s ease; position:relative; user-select:none;
  }
  .day:hover:not(.empty):not(.today):not(.blocked) { background:var(--pink-pale); color:var(--pink); }
  .day.empty   { cursor:default; }
  .day.today   { background:var(--pink); color:#fff; font-weight:700; box-shadow:0 4px 14px rgba(232,121,160,0.45); }
  .day.marked  { background:var(--pink-pale); color:var(--pink); font-weight:600; }
  .day.marked::after {
    content:''; position:absolute; bottom:3px;
    width:4px; height:4px; border-radius:50%; background:var(--pink);
  }
  .day.blocked { background:var(--red-pale); color:var(--red); font-weight:600; cursor:default; }
  .day.blocked::after {
    content:''; position:absolute; bottom:3px;
    width:4px; height:4px; border-radius:50%; background:var(--red);
  }
  .day.selected { outline:2px solid var(--pink); outline-offset:1px; }
  .day.weekend  { color:#ccc; cursor:default; }
  .day.past     { color:#ccc; cursor:default; }

  /* ── PANEL LATERAL ── */
  .side-panel { flex:1; min-width:260px; display:flex; flex-direction:column; gap:16px; }
  .panel-card { background:#fff; border-radius:24px; padding:22px 20px; box-shadow:0 4px 20px rgba(0,0,0,0.05); transition: background 0.3s; }
  .panel-title {
    font-size:0.7rem; font-weight:500; letter-spacing:1.5px;
    text-transform:uppercase; color:var(--gray); margin-bottom:12px;
  }
  .appt-list { display:flex; flex-direction:column; gap:8px; }
  .appt-item {
    display:flex; align-items:center; gap:10px;
    padding:10px 14px; background:var(--pink-pale);
    border-radius:12px; animation:fadeUp 0.3s ease forwards; opacity:0;
  }
  .appt-item .appt-dot  { width:8px; height:8px; border-radius:50%; background:var(--pink); flex-shrink:0; }
  .appt-item .appt-text { font-size:0.82rem; color:var(--dark); flex:1; }
  .appt-item .appt-time { font-size:0.78rem; color:var(--pink); font-weight:500; }
  .appt-item .appt-cancel { font-size:0.75rem; color:var(--red); cursor:pointer; margin-left:4px; }
  .appt-item .appt-cancel:hover { text-decoration:underline; }
  .appt-item.ocupado { background:var(--gray-pale); }
  .appt-item.ocupado .appt-dot  { background:var(--gray-mid); }
  .appt-item.ocupado .appt-text { color:var(--gray); }
  .appt-item.ocupado .appt-time { color:var(--gray-mid); font-weight:400; }
  .appt-section-label {
    font-size:0.68rem; font-weight:500; letter-spacing:1px;
    text-transform:uppercase; color:var(--gray-mid); padding:4px 2px 2px;
  }
  @keyframes fadeUp {
    from{opacity:0;transform:translateY(6px);}
    to{opacity:1;transform:translateY(0);}
  }
  .empty-appt { text-align:center; font-size:0.8rem; color:#ccc; padding:10px 0; }
  .agenda-item {
    background: var(--agenda-bg); border-radius:12px;
    padding:14px 16px; border-left:4px solid var(--pink); margin-bottom:10px;
  }
  .agenda-item p { font-size:0.85rem; color:var(--dark); margin:4px 0; }
  .agenda-item strong { color:var(--pink); }

  /* ── BOTONES ── */
  .btn-group { display:flex; gap:8px; margin-top:14px; flex-wrap:wrap; }
  .add-btn {
    flex:1; padding:11px; background:var(--pink); color:#fff;
    border:none; border-radius:14px; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; font-weight:500; cursor:pointer; transition:all 0.2s;
  }
  .add-btn:hover { background:#d4638c; transform:translateY(-1px); }
  .block-btn {
    flex:1; padding:11px; background:var(--red-pale); color:var(--red);
    border:1.5px solid var(--red); border-radius:14px;
    font-family:'DM Sans',sans-serif; font-size:0.88rem; cursor:pointer; transition:all 0.2s;
  }
  .block-btn:hover { background:var(--red); color:#fff; }
  .unblock-btn {
    flex:1; padding:11px; background:#eee; color:var(--gray);
    border:none; border-radius:14px; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; cursor:pointer; transition:all 0.2s;
  }
  .unblock-btn:hover { background:#ddd; }
  .back-btn {
    display:block; text-align:center; padding:11px;
    background:#f5f5f5; color:var(--gray); border:none;
    border-radius:14px; text-decoration:none;
    font-family:'DM Sans',sans-serif; font-size:0.88rem; transition:all 0.2s;
  }
  .back-btn:hover { background:#eee; }

  /* ── STATUS ── */
  .status-msg {
    padding:10px 14px; border-radius:10px;
    font-size:0.83rem; margin-bottom:12px; text-align:center;
  }
  .status-success { background: var(--status-success-bg); color: var(--status-success-text); }
  .status-error   { background:var(--red-pale); color:var(--red); }
  .status-warning { background: var(--status-warning-bg); color: var(--status-warning-text); }
  .afectados-box {
    background:#fff7f0; border-left:4px solid var(--red);
    border-radius:12px; padding:12px 14px;
    font-size:0.83rem; color:var(--dark); margin-bottom:12px;
  }
  .afectados-box strong { color:var(--red); }

  /* ── MODALES ── */
  .modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.3); backdrop-filter:blur(4px);
    z-index:100; align-items:center; justify-content:center;
    padding: 16px;
  }
  .modal-overlay.open { display:flex; }
  .modal {
    background:#fff; border-radius:24px; padding:28px 24px; width:100%; max-width:340px;
    box-shadow:0 20px 60px rgba(0,0,0,0.15); animation:popIn 0.2s ease;
  }
  @keyframes popIn {
    from{opacity:0;transform:scale(0.92);}
    to{opacity:1;transform:scale(1);}
  }
  .modal h3 { font-family:'Playfair Display',serif; font-size:1.3rem; color:var(--dark); margin-bottom:6px; }
  .modal h3 span { color:var(--pink); }
  .modal h3.danger span { color:var(--red); }
  .modal-date { font-size:0.8rem; color:var(--gray); margin-bottom:14px; }
  .modal select, .modal input, .modal textarea {
    width:100%; padding:11px 14px; border:1.5px solid #eee;
    border-radius:12px; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; color:var(--dark); margin-bottom:10px;
    outline:none; transition:border 0.2s, background 0.3s, color 0.3s; resize:none;
    appearance: none; -webkit-appearance: none;
    background-color: #fff;
  }
  .modal select:focus, .modal input:focus, .modal textarea:focus { border-color:var(--pink-light); }
  .modal select.select-error, .modal input.input-error { border-color: var(--red); background: #fff8f8; }
  .field-error {
    display: none; align-items: center; gap: 5px;
    margin: -6px 0 8px; font-size: 12px; color: var(--red); font-weight: 500;
  }
  .field-error.visible { display: flex; }
  .field-error::before {
    content:''; display:inline-block; width:13px; height:13px; flex-shrink:0;
    border-radius:50%; background-color:var(--red);
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath d='M8 4v5M8 11v1' stroke='white' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
    background-size:13px; background-repeat:no-repeat; background-position:center;
  }
  .modal-btns { display:flex; gap:8px; margin-top:6px; }
  .btn-cancel {
    flex:1; padding:11px; border:1.5px solid #eee; background:none;
    border-radius:12px; cursor:pointer; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; color:var(--gray); transition:background 0.2s;
  }
  .btn-cancel:hover { background:#f5f5f5; }
  .btn-save {
    flex:1; padding:11px; background:var(--pink); color:#fff; border:none;
    border-radius:12px; cursor:pointer; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; font-weight:500; transition:background 0.2s;
  }
  .btn-save:hover { background:#d4638c; }
  .btn-block-confirm {
    flex:1; padding:11px; background:var(--red); color:#fff; border:none;
    border-radius:12px; cursor:pointer; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; font-weight:500;
  }
  .btn-block-confirm:hover { background:#c04444; }
  .horas-loading { font-size:0.8rem; color:var(--gray); text-align:center; padding:8px 0; }

  /* ── MODAL CONFIRMACIÓN ── */
  .confirm-modal {
    background:#fff; border-radius:24px; padding:28px 24px; width:100%; max-width:300px;
    box-shadow:0 20px 60px rgba(0,0,0,0.15); animation:popIn 0.2s ease; text-align:center;
  }
  .confirm-modal .confirm-icon { font-size:2rem; margin-bottom:10px; }
  .confirm-modal h4 {
    font-family:'Playfair Display',serif; font-size:1.1rem; font-weight:700;
    color:var(--dark); margin-bottom:8px;
  }
  .confirm-modal p { font-size:0.84rem; color:var(--gray); margin-bottom:20px; line-height:1.5; }
  .confirm-modal .confirm-btns { display:flex; gap:8px; }
  .confirm-modal .btn-confirm-ok {
    flex:1; padding:11px; border:none; border-radius:12px;
    font-family:'DM Sans',sans-serif; font-size:0.88rem;
    font-weight:500; cursor:pointer; transition:background 0.2s;
  }
  .confirm-modal .btn-confirm-ok.danger  { background:var(--red); color:#fff; }
  .confirm-modal .btn-confirm-ok.danger:hover  { background:#c04444; }
  .confirm-modal .btn-confirm-ok.warning { background:var(--pink); color:#fff; }
  .confirm-modal .btn-confirm-ok.warning:hover { background:#d4638c; }

  /* ── TOASTS ── */
  .toast-container {
    position:fixed; top:20px; right:20px; z-index:9999;
    display:flex; flex-direction:column; gap:10px; pointer-events:none;
  }
  .toast {
    display:flex; align-items:center; gap:10px;
    padding:13px 16px; border-radius:14px;
    font-size:13px; font-weight:500;
    min-width:240px; max-width:320px;
    pointer-events:all;
    animation:toastIn 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards;
  }
  .toast.toast-success { background:var(--green-pale); color:var(--green); border-left:4px solid var(--green); box-shadow:0 6px 20px rgba(90,158,111,0.15); }
  .toast.toast-error   { background:var(--red-pale);   color:var(--red);   border-left:4px solid var(--red);   box-shadow:0 6px 20px rgba(224,85,85,0.15); }
  .toast.toast-warning { background: var(--status-warning-bg); color: var(--status-warning-text); border-left:4px solid #f0ad00; box-shadow:0 6px 20px rgba(240,173,0,0.15); }
  .toast-icon  { font-size:16px; flex-shrink:0; }
  .toast-msg   { flex:1; line-height:1.4; }
  .toast-close { background:none; border:none; cursor:pointer; color:inherit; opacity:0.4; font-size:16px; line-height:1; padding:0; transition:opacity 0.2s; }
  .toast-close:hover { opacity:0.8; }
  @keyframes toastIn  { from{transform:translateX(60px);opacity:0;} to{transform:translateX(0);opacity:1;} }
  @keyframes toastOut { to{transform:translateX(60px);opacity:0;} }
  .toast.removing { animation:toastOut 0.25s ease forwards; }

  /* ── RESPONSIVE ── */
  @media (max-width: 700px) {
    body { padding: 12px; }
    .page-wrapper { flex-direction: column; gap: 16px; }
    .card { width: 100%; padding: 24px 18px 20px; border-radius: 24px; }
    .title-turnos, .title-month { font-size: 1.7rem; }
    .side-panel { width: 100%; }
    .admin-header .title-turnos,
    .admin-header .title-month { font-size: 1.6rem; }
  }

  /* ════════════════════════
     TEMA OSCURO
     Se activa agregando la clase .tema-oscuro al <html>
  ════════════════════════ */
  html.tema-oscuro body {
    background: #15151a;
    color: #eee;
  }

  html.tema-oscuro .title-turnos,
  html.tema-oscuro .admin-header .title-turnos {
    color: #f2f2f2;
  }

  html.tema-oscuro .card,
  html.tema-oscuro .panel-card {
    background: #1e1e24;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  }

  html.tema-oscuro .card::before {
    background: radial-gradient(circle, rgba(232,121,160,0.1) 0%, transparent 70%);
  }

  html.tema-oscuro .admin-card { background: #1e1e24; box-shadow: 0 18px 50px rgba(0,0,0,0.4); }
  html.tema-oscuro .admin-tabs { background: #26262e; }
  html.tema-oscuro .admin-tab { color: #d9a0ae; }
  html.tema-oscuro .admin-tab:hover:not(.active) { background: rgba(255,255,255,0.06); }
  html.tema-oscuro .admin-tab.active { background: #33333c; color: #f2f2f2; box-shadow: none; }
  html.tema-oscuro .admin-tab .count { background: rgba(255,255,255,0.08); }
  html.tema-oscuro .admin-tab.active .count-pendientes { background: rgba(240,179,187,0.18); color: #F0B3BB; }
  html.tema-oscuro .admin-tab.active .count-realizadas { background: rgba(211,232,216,0.16); color: #D3E8D8; }
  html.tema-oscuro .admin-tab.active .count-canceladas { background: rgba(217,154,154,0.18); color: #E3AAAA; }
  html.tema-oscuro .panel-heading h2 { color: #f2f2f2; }
  html.tema-oscuro .panel-heading p { color: #999; }
  html.tema-oscuro .buscador-turnos { background: #26262e; border-color: #33333c; color: #eee; }
  html.tema-oscuro .admin-table thead th { background: #1e1e24; color: #d9a0ae; }
  html.tema-oscuro .admin-table tbody td { background: #26262e; color: #eee; }
  html.tema-oscuro .admin-table tbody tr:hover td { background: #2f2f38; }
  html.tema-oscuro .hora-pendientes { background: rgba(240,179,187,0.16); color: #F0B3BB; }
  html.tema-oscuro .hora-realizadas { background: rgba(211,232,216,0.16); color: #D3E8D8; }
  html.tema-oscuro .hora-canceladas { background: rgba(217,154,154,0.16); color: #E3AAAA; }
  html.tema-oscuro .empty-state { color: #888; }

  html.tema-oscuro .nav-btn:hover {
    background: rgba(232,121,160,0.15);
  }

  html.tema-oscuro .nav-label {
    color: #999;
  }

  html.tema-oscuro .weekday {
    color: #999;
  }

  html.tema-oscuro .day {
    color: #eee;
  }

  html.tema-oscuro .day:hover:not(.empty):not(.today):not(.blocked) {
    background: rgba(232,121,160,0.15);
  }

  html.tema-oscuro .day.marked {
    background: rgba(232,121,160,0.15);
  }

  html.tema-oscuro .day.blocked {
    background: rgba(224,85,85,0.15);
  }

  html.tema-oscuro .day.weekend,
  html.tema-oscuro .day.past {
    color: #555;
  }

  html.tema-oscuro .panel-title {
    color: #999;
  }

  html.tema-oscuro .appt-item {
    background: rgba(232,121,160,0.12);
  }

  html.tema-oscuro .appt-item .appt-text {
    color: #eee;
  }

  html.tema-oscuro .appt-item.ocupado {
    background: #26262e;
  }

  html.tema-oscuro .appt-item.ocupado .appt-text {
    color: #999;
  }

  html.tema-oscuro .empty-appt {
    color: #555;
  }

  html.tema-oscuro .agenda-item {
    background: #26262e;
  }

  html.tema-oscuro .agenda-item p {
    color: #eee;
  }

  html.tema-oscuro .unblock-btn {
    background: #2a2a31;
    color: #ccc;
  }

  html.tema-oscuro .unblock-btn:hover {
    background: #33333c;
  }

  html.tema-oscuro .back-btn {
    background: #1e1e24;
    color: #aaa;
  }

  html.tema-oscuro .back-btn:hover {
    background: #26262e;
  }

  html.tema-oscuro .status-success {
    background: rgba(163,177,138,0.15);
    color: #b9c99e;
  }

  html.tema-oscuro .status-error {
    background: rgba(224,85,85,0.15);
    color: #f19d9d;
  }

  html.tema-oscuro .status-warning {
    background: rgba(240,173,0,0.15);
    color: #e0b843;
  }

  html.tema-oscuro .afectados-box {
    background: rgba(224,85,85,0.1);
    color: #eee;
  }

  html.tema-oscuro .modal-overlay {
    background: rgba(0,0,0,0.6);
  }

  html.tema-oscuro .modal,
  html.tema-oscuro .confirm-modal {
    background: #1e1e24;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
  }

  html.tema-oscuro .modal h3,
  html.tema-oscuro .confirm-modal h4 {
    color: #f2f2f2;
  }

  html.tema-oscuro .modal-date,
  html.tema-oscuro .confirm-modal p {
    color: #999;
  }

  html.tema-oscuro .modal select,
  html.tema-oscuro .modal input,
  html.tema-oscuro .modal textarea {
    background-color: #26262e;
    border-color: #33333c;
    color: #eee;
  }

  html.tema-oscuro .modal select.select-error,
  html.tema-oscuro .modal input.input-error {
    background: rgba(224,85,85,0.08);
  }

  html.tema-oscuro .btn-cancel {
    border-color: #33333c;
    color: #ccc;
  }

  html.tema-oscuro .btn-cancel:hover {
    background: #26262e;
  }

  html.tema-oscuro .horas-loading {
    color: #999;
  }

  html.tema-oscuro .toast.toast-warning {
    background: rgba(240,173,0,0.15);
    color: #e0b843;
  }

  html.tema-oscuro .toast.toast-success {
    background: rgba(90,158,111,0.15);
    color: #8fc9a0;
  }

  html.tema-oscuro .toast.toast-error {
    background: rgba(224,85,85,0.15);
    color: #f19d9d;
  }
</style>
<?php include __DIR__ . '/../Tema/TemaHead.php'; ?>
</head>
<body class="<?= $id_Rol == 3 ? 'vista-admin' : '' ?>">

<!-- Toasts -->
<div class="toast-container" id="toastContainer"></div>

<?php if ($id_Rol == 3): ?>
<!-- ════ VISTA ADMIN ════ -->
<?php
$tabs_admin = [
  'pendientes' => ['label' => 'Pendientes', 'titulo' => 'Citas pendientes', 'desc' => 'Turnos que todavía no se atendieron',      'datos' => $turnos_pendientes],
  'realizadas' => ['label' => 'Realizadas', 'titulo' => 'Citas realizadas', 'desc' => 'Servicios que los terapeutas ya finalizaron', 'datos' => $turnos_completados],
  'canceladas' => ['label' => 'Canceladas', 'titulo' => 'Citas canceladas', 'desc' => 'Turnos que se dieron de baja',                'datos' => $turnos_cancelados],
];
?>
<div class="admin-wrapper">
  <div class="admin-header">
    <div class="title-turnos">TURNOS <span class="sparkle">✦</span></div>
    <div class="title-month">HISTORIAL GENERAL</div>
  </div>

  <div class="admin-card">
    <div class="admin-tabs" role="tablist">
      <?php $primero = true; foreach ($tabs_admin as $clave => $tab): ?>
      <button type="button" role="tab" class="admin-tab<?= $primero ? ' active' : '' ?>"
              data-tab="<?= $clave ?>" aria-selected="<?= $primero ? 'true' : 'false' ?>">
        <span class="dot dot-<?= $clave ?>"></span>
        <?= $tab['label'] ?>
        <span class="count count-<?= $clave ?>"><?= count($tab['datos']) ?></span>
      </button>
      <?php $primero = false; endforeach; ?>
    </div>

    <?php $primero = true; foreach ($tabs_admin as $clave => $tab): ?>
    <section class="tab-panel<?= $primero ? ' active' : '' ?>" id="tab-<?= $clave ?>" role="tabpanel">
      <div class="panel-heading">
        <div>
          <h2><?= $tab['titulo'] ?></h2>
          <p><?= $tab['desc'] ?></p>
        </div>
        <input type="search" class="buscador-turnos"
               placeholder="Buscar cliente o terapeuta…" autocomplete="off">
      </div>

      <?php if (empty($tab['datos'])): ?>
        <div class="empty-state"><span class="spark">✦</span>No hay <?= strtolower($tab['titulo']) ?></div>
      <?php else: ?>
      <div class="table-scroll">
        <table class="admin-table">
          <thead><tr><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Terapeuta</th><th>Servicio</th></tr></thead>
          <tbody>
            <?php foreach ($tab['datos'] as $t): ?>
            <tr data-busqueda="<?= htmlspecialchars($t['Cliente'] . ' ' . $t['Terapeuta']) ?>">
              <td class="td-fecha"><?= date('d/m/Y', strtotime($t['Fecha_Turno'])) ?></td>
              <td><span class="hora-pill hora-<?= $clave ?>"><?= htmlspecialchars($t['Hora_Turno']) ?> hs</span></td>
              <td class="td-nombre"><?= htmlspecialchars($t['Cliente']) ?></td>
              <td><?= htmlspecialchars($t['Terapeuta']) ?></td>
              <td class="td-servicio"><?= htmlspecialchars($t['Nombre_servicio']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="empty-state sin-resultados"><span class="spark">✦</span>No se encontraron turnos</div>
      <?php endif; ?>
    </section>
    <?php $primero = false; endforeach; ?>
  </div>

  <a href="../index.php" class="back-btn">← Volver al inicio</a>
</div>

<script>
(function () {
  const tabs   = document.querySelectorAll('.admin-tab');
  const panels = document.querySelectorAll('.tab-panel');

  function mostrar(clave) {
    tabs.forEach(t => {
      const activa = t.dataset.tab === clave;
      t.classList.toggle('active', activa);
      t.setAttribute('aria-selected', activa);
    });
    panels.forEach(p => p.classList.toggle('active', p.id === 'tab-' + clave));
  }

  tabs.forEach(t => t.addEventListener('click', () => {
    mostrar(t.dataset.tab);
    history.replaceState(null, '', '#' + t.dataset.tab);
  }));

  const inicial = location.hash.slice(1);
  if (inicial && document.getElementById('tab-' + inicial)) mostrar(inicial);

  // ── Buscador compartido entre las 3 pestañas ──
  const inputsBusqueda = document.querySelectorAll('.buscador-turnos');
  const normalizar = txt => txt.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

  function filtrarTurnos(valor) {
    const q = normalizar(valor.trim());
    panels.forEach(panel => {
      const filas = panel.querySelectorAll('tbody tr');
      let visibles = 0;
      filas.forEach(fila => {
        const coincide = normalizar(fila.dataset.busqueda).includes(q);
        fila.style.display = coincide ? '' : 'none';
        if (coincide) visibles++;
      });
      const sin = panel.querySelector('.sin-resultados');
      if (sin) sin.style.display = (filas.length && visibles === 0) ? 'block' : 'none';
    });
  }

  inputsBusqueda.forEach(input => {
    input.addEventListener('input', () => {
      inputsBusqueda.forEach(o => { if (o !== input) o.value = input.value; });
      filtrarTurnos(input.value);
    });
  });
})();
</script>

<?php else: ?>
<!-- ════ VISTA PACIENTE / TERAPEUTA ════ -->
<div class="page-wrapper">

  <!-- Calendario -->
  <div class="card">
    <div class="header">
      <div class="title-turnos">TURNOS <span class="sparkle">✦</span></div>
      <div class="title-month" id="monthTitle"></div>
    </div>
    <div class="nav">
      <button class="nav-btn" onclick="changeMonth(-1)">&#8249;</button>
      <span class="nav-label" id="yearLabel"></span>
      <button class="nav-btn" onclick="changeMonth(1)">&#8250;</button>
    </div>
    <div class="weekdays">
      <div class="weekday">L</div><div class="weekday">M</div><div class="weekday">M</div>
      <div class="weekday">J</div><div class="weekday">V</div>
      <div class="weekday">S</div><div class="weekday">D</div>
    </div>
    <div class="days" id="daysGrid"></div>
  </div>

  <!-- Panel lateral -->
  <div class="side-panel">

    <?php
    $status = $_GET['status'] ?? '';
    $statusMap = [
      'success'        => ['success', '¡Operación realizada con éxito!'],
      'error'          => ['error',   'Hubo un error. Intentá de nuevo.'],
      'bloqueado'      => ['warning', 'Ese terapeuta no está disponible ese día.'],
      'fin_semana'     => ['warning', 'No se pueden agendar turnos los fines de semana.'],
      'hora_ocupada'   => ['warning', 'Ese horario ya está ocupado. Elegí otro.'],
      'fecha_pasada'   => ['warning', 'No podés agendar turnos en fechas pasadas.'],
      'ya_bloqueado'   => ['warning', 'Ese día ya estaba bloqueado.'],
      'bloqueado_ok'   => ['error',   'Día bloqueado. Los turnos fueron cancelados.'],
      'desbloqueado_ok'=> ['success', 'Día desbloqueado correctamente.'],
    ];
    if ($status && isset($statusMap[$status])):
      [$tipo, $msg] = $statusMap[$status];
    ?>
      <div class="status-msg status-<?= $tipo ?>"><?= $msg ?></div>
    <?php endif; ?>

    <?php if (!empty($pacientes_afectados)): ?>
      <div class="afectados-box">
        <strong>Turnos cancelados el <?= htmlspecialchars($fecha_bloqueada) ?>:</strong><br>
        <?php foreach ($pacientes_afectados as $p): ?>
          · <?= htmlspecialchars($p['Persona_Nombre'] . ' ' . $p['Persona_Apellido']) ?><br>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="panel-card">
      <div class="panel-title" id="panelTitulo">Seleccioná un día</div>
      <div class="appt-list" id="apptList">
        <div class="empty-appt">—</div>
      </div>

      <?php if ($id_Rol == 0): ?>
      <div class="btn-group" id="btnGroup" style="display:none;">
        <button class="add-btn" onclick="openModalAgregar()">+ Agregar turno</button>
      </div>
      <?php endif; ?>

      <?php if ($id_Rol == 2): ?>
      <div class="btn-group" id="btnGroup" style="display:none;">
        <button class="block-btn"   id="btnBloquear"    onclick="openModalBloquear()">🔒 Bloquear día</button>
        <button class="unblock-btn" id="btnDesbloquear" onclick="desbloquearDia()" style="display:none;">🔓 Desbloquear</button>
      </div>
      <?php endif; ?>
    </div>

    <?php if ($id_Rol == 2): ?>
    <div class="panel-card">
      <div class="panel-title">Agenda de hoy</div>
      <?php if ($agendaHoy && $agendaHoy->num_rows > 0): ?>
        <?php while ($cita = $agendaHoy->fetch_assoc()): ?>
          <div class="agenda-item">
            <p><strong><?= substr($cita['Hora_Turno'], 0, 5) ?> hs</strong> · <?= htmlspecialchars($cita['Nombre_servicio']) ?></p>
            <p><?= htmlspecialchars($cita['Cliente_Nombre'] . ' ' . $cita['Cliente_Apellido']) ?></p>
            <p>📞 <?= htmlspecialchars($cita['Cliente_Telefono']) ?></p>
            <?php if ($cita['Estado_Turno'] === 'Pendiente'): ?>
            <form method="POST" action="TurnoController.php?action=completar" style="margin-top:8px;">
              <input type="hidden" name="id_turno" value="<?= $cita['id_Turno'] ?>">
              <button type="submit" class="add-btn" style="font-size:0.8rem;padding:8px;">✓ Finalizar servicio</button>
            </form>
            <?php else: ?>
            <p style="color:var(--gray);font-size:0.8rem;"><?= htmlspecialchars($cita['Estado_Turno']) ?></p>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="empty-appt">Sin citas para hoy</div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($id_Rol == 2 && $pendientesAnteriores && $pendientesAnteriores->num_rows > 0): ?>
    <div class="panel-card">
      <div class="panel-title">Pendientes de días anteriores</div>
      <?php while ($cita = $pendientesAnteriores->fetch_assoc()): ?>
        <div class="agenda-item">
          <p><strong><?= date('d/m/Y', strtotime($cita['Fecha_Turno'])) ?> · <?= substr($cita['Hora_Turno'], 0, 5) ?> hs</strong> · <?= htmlspecialchars($cita['Nombre_servicio']) ?></p>
          <p><?= htmlspecialchars($cita['Cliente_Nombre'] . ' ' . $cita['Cliente_Apellido']) ?></p>
          <p>📞 <?= htmlspecialchars($cita['Cliente_Telefono']) ?></p>
          <form method="POST" action="TurnoController.php?action=completar" style="margin-top:8px;">
            <input type="hidden" name="id_turno" value="<?= $cita['id_Turno'] ?>">
            <button type="submit" class="add-btn" style="font-size:0.8rem;padding:8px;">✓ Finalizar servicio</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
    <?php endif; ?>

    <a href="../index.php" class="back-btn">← Volver al inicio</a>
  </div>
</div>

<!-- Modal agregar turno (Paciente) -->
<?php if ($id_Rol == 0): ?>
<div class="modal-overlay" id="modalAgregar">
  <div class="modal">
    <h3>Nuevo <span>turno</span></h3>
    <div class="modal-date" id="modalDateAgregar"></div>
    <!-- novalidate: validamos manualmente con JS -->
    <form method="POST" action="TurnoController.php?action=crear" id="formAgregar" novalidate>
      <input type="hidden" name="Fecha_Turno" id="inputFechaAgregar">

      <select name="id_Servicio" id="selectServicio">
        <option value="">Seleccioná un servicio...</option>
        <?php foreach ($servicios_arr as $s): ?>
          <option value="<?= $s['id_Servicio'] ?>">
            <?= htmlspecialchars($s['Nombre_servicio']) ?> — $<?= $s['Costo'] ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="field-error" id="err-servicio"></div>

      <select name="id_Empleado" id="selectTerapeuta" onchange="cargarHoras()">
        <option value="">Seleccioná un terapeuta...</option>
      </select>
      <div class="field-error" id="err-terapeuta"></div>

      <div id="horasContainer">
        <select name="Hora_Turno" id="selectHora" disabled>
          <option value="">Primero elegí un terapeuta</option>
        </select>
        <div class="horas-loading" id="horasLoading" style="display:none;">Cargando horarios...</div>
      </div>
      <div class="field-error" id="err-hora"></div>

      <div class="modal-btns">
        <button type="button" class="btn-cancel" onclick="closeModal('modalAgregar')">Cancelar</button>
        <button type="button" class="btn-save" onclick="submitAgregar()">Confirmar turno</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- Modal bloquear día (Terapeuta) -->
<?php if ($id_Rol == 2): ?>
<div class="modal-overlay" id="modalBloquear">
  <div class="modal">
    <h3 class="danger">Bloquear <span>día</span></h3>
    <div class="modal-date" id="modalDateBloquear"></div>
    <form method="POST" action="TurnoController.php?action=bloquear" id="formBloquear" novalidate>
      <input type="hidden" name="fecha" id="inputFechaBloquear">
      <textarea name="motivo" placeholder="Motivo (opcional)" rows="3"></textarea>
      <div class="modal-btns">
        <button type="button" class="btn-cancel" onclick="closeModal('modalBloquear')">Cancelar</button>
        <button type="button" class="btn-block-confirm" onclick="submitBloquear()">🔒 Confirmar bloqueo</button>
      </div>
    </form>
  </div>
</div>

<form method="POST" action="TurnoController.php?action=desbloquear" id="formDesbloquear" style="display:none;">
  <input type="hidden" name="fecha" id="inputFechaDesbloquear">
</form>
<?php endif; ?>

<!-- Modal confirmación genérico -->
<div class="modal-overlay" id="modalConfirm">
  <div class="confirm-modal">
    <div class="confirm-icon" id="confirmIcon">⚠️</div>
    <h4 id="confirmTitle">¿Estás seguro?</h4>
    <p id="confirmMsg"></p>
    <div class="confirm-btns">
      <button class="btn-cancel" onclick="closeModal('modalConfirm')">Cancelar</button>
      <button class="btn-confirm-ok danger" id="confirmOkBtn">Confirmar</button>
    </div>
  </div>
</div>

<script>
  const MONTHS_ES      = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
  const MONTHS_ES_FULL = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

  const id_Rol     = <?= intval($id_Rol) ?>;
  const turnosMes  = <?= $turnos_json ?>;
  const bloqueados = <?= $bloqueados_json ?>;

  let today        = new Date();
  let current      = new Date(today.getFullYear(), today.getMonth(), 1);
  let selectedDate = null;

  /* ══════════════════════════════
     TOASTS
  ══════════════════════════════ */
  function showToast(msg, type = 'success') {
    const icons = { success: '✓', error: '✕', warning: '⚠' };
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
      <span class="toast-icon">${icons[type] || '!'}</span>
      <span class="toast-msg">${msg}</span>
      <button class="toast-close" onclick="removeToast(this.parentElement)">×</button>
    `;
    container.appendChild(toast);
    setTimeout(() => removeToast(toast), 3500);
  }
  function removeToast(toast) {
    if (!toast || toast.classList.contains('removing')) return;
    toast.classList.add('removing');
    toast.addEventListener('animationend', () => toast.remove());
  }

  /* ══════════════════════════════
     VALIDACIÓN MODALES
  ══════════════════════════════ */
  function setFieldError(id, msg) {
    const el = document.getElementById(id);
    const sel = el ? el.previousElementSibling : null;
    if (el) { el.textContent = msg; el.classList.toggle('visible', !!msg); }
    if (sel && (sel.tagName === 'SELECT' || sel.tagName === 'INPUT')) {
      sel.classList.toggle('select-error', !!msg);
    }
  }

  function submitAgregar() {
    let ok = true;
    const servicio  = document.getElementById('selectServicio').value;
    const terapeuta = document.getElementById('selectTerapeuta').value;
    const hora      = document.getElementById('selectHora').value;

    setFieldError('err-servicio',  !servicio  ? 'Seleccioná un servicio' : '');
    setFieldError('err-terapeuta', !terapeuta ? 'Seleccioná un terapeuta' : '');
    setFieldError('err-hora',      !hora      ? 'Seleccioná un horario' : '');

    if (!servicio || !terapeuta || !hora) {
      showToast('Completá todos los campos antes de confirmar', 'warning');
      return;
    }
    document.getElementById('formAgregar').submit();
  }

  function submitBloquear() {
    const fecha = document.getElementById('inputFechaBloquear').value;
    if (!fecha) { showToast('No hay fecha seleccionada', 'error'); return; }
    document.getElementById('formBloquear').submit();
  }

  /* ══════════════════════════════
     CALENDARIO
  ══════════════════════════════ */
  function dateKey(y, m, d) {
    return `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
  }
  function turnosPorFecha() {
    const map = {};
    turnosMes.forEach(t => {
      if (!map[t.Fecha_Turno]) map[t.Fecha_Turno] = [];
      map[t.Fecha_Turno].push(t);
    });
    return map;
  }
  function esFinDeSemana(y, m, d) {
    const dow = new Date(y, m, d).getDay();
    return dow === 0 || dow === 6;
  }

  function renderCalendar() {
    const year  = current.getFullYear();
    const month = current.getMonth();
    document.getElementById('monthTitle').textContent = MONTHS_ES[month];
    document.getElementById('yearLabel').textContent  = year;

    const grid        = document.getElementById('daysGrid');
    grid.innerHTML    = '';
    const firstDay    = new Date(year, month, 1).getDay();
    const offset      = firstDay === 0 ? 6 : firstDay - 1;
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const map         = turnosPorFecha();

    for (let i = 0; i < offset; i++) {
      const el = document.createElement('div');
      el.className = 'day empty';
      grid.appendChild(el);
    }
    for (let d = 1; d <= daysInMonth; d++) {
      const el  = document.createElement('div');
      const key = dateKey(year, month, d);
      el.className   = 'day';
      el.textContent = d;

      const isToday   = today.getFullYear() === year && today.getMonth() === month && today.getDate() === d;
      const isWeekend = esFinDeSemana(year, month, d);
      const isBlocked = bloqueados.includes(key);
      const hasTurnos = map[key] && map[key].length > 0;
      const isPast    = new Date(year, month, d) < new Date(today.getFullYear(), today.getMonth(), today.getDate());

      if (isWeekend || isBlocked) {
        el.classList.add('blocked');
      } else if (isPast) {
        el.classList.add('past');
      } else if (isToday) {
        el.classList.add('today');
      } else if (hasTurnos) {
        el.classList.add('marked');
      }

      if (selectedDate === key) el.classList.add('selected');
      if (!isWeekend && (!isPast || id_Rol === 2)) {
        el.addEventListener('click', () => selectDay(year, month, d));
      }
      grid.appendChild(el);
    }
  }

  function selectDay(y, m, d) {
    selectedDate = dateKey(y, m, d);
    renderCalendar();
    renderAppointments();

    const isBlocked = bloqueados.includes(selectedDate) || esFinDeSemana(y, m, d);
    const btnGroup  = document.getElementById('btnGroup');
    if (btnGroup) btnGroup.style.display = 'flex';

    if (id_Rol === 2) {
      const btnB = document.getElementById('btnBloquear');
      const btnD = document.getElementById('btnDesbloquear');
      if (btnB) btnB.style.display = isBlocked ? 'none' : 'flex';
      if (btnD) btnD.style.display = bloqueados.includes(selectedDate) ? 'flex' : 'none';
    }
  }

  function renderAppointments() {
    const list  = document.getElementById('apptList');
    const title = document.getElementById('panelTitulo');
    list.innerHTML = '';

    if (!selectedDate) {
      title.textContent = 'Seleccioná un día';
      list.innerHTML = '<div class="empty-appt">—</div>';
      return;
    }

    const [y, m, d] = selectedDate.split('-').map(Number);
    title.textContent = `${d} de ${MONTHS_ES_FULL[m-1]} de ${y}`;

    const isBlocked = bloqueados.includes(selectedDate);
    const isWeekend = esFinDeSemana(y, m-1, d);

    if (isWeekend) {
      list.innerHTML = '<div class="empty-appt">🚫 Fin de semana</div>';
      return;
    }
    if (isBlocked && id_Rol === 2) {
      list.innerHTML = '<div class="empty-appt" style="color:var(--red)">🔒 Día bloqueado</div>';
      return;
    }
    if (isBlocked && id_Rol === 0) {
      list.innerHTML = '<div class="empty-appt" style="color:var(--red)">🔒 Sin disponibilidad este día</div>';
      return;
    }

    const map   = turnosPorFecha();
    const appts = map[selectedDate] || [];

    if (id_Rol === 2) {
      list.innerHTML = '<div class="empty-appt" style="font-size:0.75rem;">Cargando turnos...</div>';
      fetch(`TurnoController.php?action=turnosDelDiaTerapeuta&fecha=${selectedDate}`)
        .then(r => r.json())
        .then(turnos => {
          list.innerHTML = '';
          if (turnos.length === 0) {
            list.innerHTML = '<div class="empty-appt">Sin turnos este día ✦</div>';
            return;
          }
          turnos.forEach((a, i) => {
            const el = document.createElement('div');
            el.className = 'appt-item';
            el.style.animationDelay = `${i * 0.06}s`;
            el.innerHTML = `
              <div class="appt-dot"></div>
              <span class="appt-text">${a.Paciente_Nombre} ${a.Paciente_Apellido} · ${a.Nombre_servicio}</span>
              <span class="appt-time">${a.Hora_Turno}</span>
            `;
            list.appendChild(el);
          });
        })
        .catch(() => {
          list.innerHTML = '<div class="empty-appt">Sin turnos este día ✦</div>';
        });
      return;
    }

    if (appts.length > 0) {
      const labelMio = document.createElement('div');
      labelMio.className = 'appt-section-label';
      labelMio.textContent = 'Mis turnos';
      list.appendChild(labelMio);

      appts.forEach((a, i) => {
        const el = document.createElement('div');
        el.className = 'appt-item';
        el.style.animationDelay = `${i * 0.06}s`;
        const nombre = `${a.Terapeuta_Nombre} ${a.Terapeuta_Apellido}`;
        el.innerHTML = `
          <div class="appt-dot"></div>
          <span class="appt-text">${nombre} · ${a.Nombre_servicio}</span>
          <span class="appt-time">${a.Hora_Turno}</span>
          ${a.Estado_Turno !== 'Completado'
            ? `<span class="appt-cancel" onclick="cancelarTurno(${a.id_Turno}, '${nombre}')">✕</span>`
            : ''}
        `;
        list.appendChild(el);
      });
    }

    const labelOcupados = document.createElement('div');
    labelOcupados.className = 'appt-section-label';
    labelOcupados.id = 'labelOcupados';
    labelOcupados.textContent = 'Horarios ocupados';
    labelOcupados.style.display = 'none';
    list.appendChild(labelOcupados);

    const loadingEl = document.createElement('div');
    loadingEl.className = 'empty-appt';
    loadingEl.id = 'ocupadosLoading';
    loadingEl.style.fontSize = '0.75rem';
    loadingEl.textContent = appts.length === 0 ? 'Cargando disponibilidad...' : '';
    list.appendChild(loadingEl);

    fetch(`TurnoController.php?action=horasOcupadasDelDia&fecha=${selectedDate}`)
      .then(r => r.json())
      .then(ocupados => {
        const loading = document.getElementById('ocupadosLoading');
        if (loading) loading.remove();
        if (ocupados.length === 0) {
          if (appts.length === 0) list.innerHTML = '<div class="empty-appt">Sin turnos este día ✦</div>';
          return;
        }
        const lbl = document.getElementById('labelOcupados');
        if (lbl) lbl.style.display = 'block';
        ocupados.forEach((o, i) => {
          const el = document.createElement('div');
          el.className = 'appt-item ocupado';
          el.style.animationDelay = `${i * 0.05}s`;
          el.innerHTML = `
            <div class="appt-dot"></div>
            <span class="appt-text">${o.Terapeuta_Nombre} ${o.Terapeuta_Apellido}</span>
            <span class="appt-time">${o.Hora_Turno} hs · ocupado</span>
          `;
          list.appendChild(el);
        });
      })
      .catch(() => {
        const loading = document.getElementById('ocupadosLoading');
        if (loading) loading.remove();
        if (appts.length === 0) list.innerHTML = '<div class="empty-appt">Sin turnos este día ✦</div>';
      });
  }

  /* ══════════════════════════════
     MODALES
  ══════════════════════════════ */
  function showConfirm({ icon = '⚠️', title, msg, btnClass = 'danger', btnText = 'Confirmar', onOk }) {
    document.getElementById('confirmIcon').textContent  = icon;
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMsg').textContent   = msg;
    const btn    = document.getElementById('confirmOkBtn');
    const newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);
    newBtn.textContent = btnText;
    newBtn.className   = `btn-confirm-ok ${btnClass}`;
    newBtn.addEventListener('click', () => { closeModal('modalConfirm'); onOk(); });
    document.getElementById('modalConfirm').classList.add('open');
  }

  function cancelarTurno(id, nombre) {
    showConfirm({
      icon: '🗑️', title: 'Cancelar turno',
      msg: `¿Querés cancelar el turno con ${nombre}?`,
      btnClass: 'danger', btnText: 'Sí, cancelar',
      onOk: () => {
        const form  = document.createElement('form');
        form.method = 'POST';
        form.action = '../Controllers/TurnoController.php?action=eliminar';
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = 'id_turno'; input.value = id;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  function openModalAgregar() {
    if (!selectedDate) {
      showToast('Seleccioná un día primero', 'warning');
      return;
    }
    if (bloqueados.includes(selectedDate)) {
      showToast('Ese día no tiene disponibilidad', 'error');
      return;
    }

    const [y, m, d] = selectedDate.split('-').map(Number);
    document.getElementById('modalDateAgregar').textContent = `${d} de ${MONTHS_ES_FULL[m-1]} de ${y}`;
    document.getElementById('inputFechaAgregar').value = selectedDate;

    // Limpiar errores anteriores
    ['err-servicio','err-terapeuta','err-hora'].forEach(id => setFieldError(id, ''));

    const selH = document.getElementById('selectHora');
    selH.innerHTML = '<option value="">Primero elegí un terapeuta</option>';
    selH.disabled  = true;

    const selT = document.getElementById('selectTerapeuta');
    selT.innerHTML = '<option value="">Cargando terapeutas...</option>';
    selT.disabled  = true;

    fetch(`TurnoController.php?action=terapeutasDisponibles&fecha=${selectedDate}`)
      .then(r => r.json())
      .then(terapeutas => {
        selT.innerHTML = '<option value="">Seleccioná un terapeuta...</option>';
        if (terapeutas.length === 0) {
          selT.innerHTML = '<option value="">Sin terapeutas disponibles</option>';
          showToast('No hay terapeutas disponibles este día', 'warning');
        } else {
          terapeutas.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t.id_Empleado;
            opt.textContent = `${t.Persona_Nombre} ${t.Persona_Apellido}`;
            selT.appendChild(opt);
          });
          selT.disabled = false;
        }
      })
      .catch(() => {
        selT.innerHTML = '<option value="">Error al cargar terapeutas</option>';
        showToast('Error al cargar terapeutas', 'error');
      });

    document.getElementById('modalAgregar').classList.add('open');
  }

  function cargarHoras() {
    const id_Emp = document.getElementById('selectTerapeuta').value;
    const fecha  = document.getElementById('inputFechaAgregar').value;
    const sel    = document.getElementById('selectHora');
    const loader = document.getElementById('horasLoading');

    if (!id_Emp || !fecha) {
      sel.innerHTML = '<option value="">Primero elegí un terapeuta</option>';
      sel.disabled  = true;
      return;
    }
    sel.disabled = true;
    loader.style.display = 'block';

    fetch(`TurnoController.php?action=horasDisponibles&id_Empleado=${id_Emp}&fecha=${fecha}`)
      .then(r => r.json())
      .then(horas => {
        loader.style.display = 'none';
        sel.innerHTML = '';
        if (horas.length === 0) {
          sel.innerHTML = '<option value="">Sin horarios disponibles</option>';
          sel.disabled  = true;
          showToast('Este terapeuta no tiene horarios disponibles ese día', 'warning');
        } else {
          sel.innerHTML = '<option value="">Elegí un horario...</option>';
          horas.forEach(h => {
            const opt = document.createElement('option');
            opt.value = h; opt.textContent = h + ' hs';
            sel.appendChild(opt);
          });
          sel.disabled = false;
        }
      })
      .catch(() => {
        loader.style.display = 'none';
        sel.innerHTML = '<option value="">Error al cargar horarios</option>';
        showToast('Error al cargar horarios', 'error');
      });
  }

  function openModalBloquear() {
    if (!selectedDate) {
      showToast('Seleccioná un día primero', 'warning');
      return;
    }
    const [y, m, d] = selectedDate.split('-').map(Number);
    document.getElementById('modalDateBloquear').textContent = `${d} de ${MONTHS_ES_FULL[m-1]} de ${y}`;
    document.getElementById('inputFechaBloquear').value = selectedDate;
    document.getElementById('modalBloquear').classList.add('open');
  }

  function desbloquearDia() {
    if (!selectedDate) return;
    const [y, m, d] = selectedDate.split('-').map(Number);
    showConfirm({
      icon: '🔓', title: 'Desbloquear día',
      msg: `¿Desbloquear el ${d} de ${MONTHS_ES_FULL[m-1]} de ${y}? Los turnos cancelados no se restauran automáticamente.`,
      btnClass: 'warning', btnText: 'Sí, desbloquear',
      onOk: () => {
        document.getElementById('inputFechaDesbloquear').value = selectedDate;
        document.getElementById('formDesbloquear').submit();
      }
    });
  }

  function closeModal(id) {
    document.getElementById(id).classList.remove('open');
  }

  document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
  });

  function changeMonth(dir) {
    current.setMonth(current.getMonth() + dir);
    selectedDate = null;
    const bg = document.getElementById('btnGroup');
    if (bg) bg.style.display = 'none';
    renderCalendar();
    renderAppointments();
  }

  selectedDate = dateKey(today.getFullYear(), today.getMonth(), today.getDate());
  renderCalendar();
  renderAppointments();
  const bg = document.getElementById('btnGroup');
  if (bg) bg.style.display = 'flex';
  if (id_Rol === 2) {
    const isBlocked = bloqueados.includes(selectedDate);
    const btnB = document.getElementById('btnBloquear');
    const btnD = document.getElementById('btnDesbloquear');
    if (btnB) btnB.style.display = isBlocked ? 'none' : 'flex';
    if (btnD) btnD.style.display = isBlocked ? 'flex'  : 'none';
  }
</script>

<?php endif; ?>
<?php include __DIR__ . '/../Views/ayuda_footer.php'; ?>

</body>
</html>