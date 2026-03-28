@extends('layouts.app')

@section('title', 'Marcas — GDA Store')

@section('content')

<div class="page-header">
    <h1>
        <span>Catálogo</span>
        Marcas
    </h1>
    <button class="btn btn-primary" onclick="openModal('create')">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nueva Marca
    </button>
</div>

<div id="alert-container"></div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="marcas-tbody">
            <tr class="loading-row">
                <td colspan="4">
                    <span class="spinner"></span>
                    <span style="margin-left:0.75rem">Cargando marcas...</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- Modal Crear / Editar --}}
<div class="modal-overlay" id="modal-form">
    <div class="modal">
        <h2 id="modal-title">Nueva Marca</h2>
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" id="field-nombre" placeholder="Ej: Nike, Adidas...">
        </div>
        <div class="form-group">
            <label>Estado</label>
            <select id="field-estado">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
        <div class="form-actions">
            <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
            <button class="btn btn-primary" id="btn-save" onclick="saveMarca()">Guardar</button>
        </div>
    </div>
</div>

{{-- Modal Confirmar Eliminar --}}
<div class="modal-overlay" id="modal-delete">
    <div class="modal">
        <h2>¿Eliminar marca?</h2>
        <p style="color:var(--muted); margin-bottom:1.5rem; font-size:0.9rem;">
            Esta acción eliminará la marca <strong id="delete-nombre" style="color:var(--text)"></strong>. ¿Estás segura?
        </p>
        <div class="form-actions">
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancelar</button>
            <button class="btn btn-danger" onclick="confirmDelete()">Sí, eliminar</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const API = '/api/marcas';
let editingId = null;
let deletingId = null;

// ── CARGAR MARCAS ──
async function loadMarcas() {
    try {
        const res  = await fetch(API);
        const json = await res.json();
        renderTable(json.data);
    } catch (e) {
        showAlert('Error al conectar con la API.', 'error');
        document.getElementById('marcas-tbody').innerHTML =
            `<tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--muted)">Sin datos</td></tr>`;
    }
}

function renderTable(marcas) {
    const tbody = document.getElementById('marcas-tbody');
    if (!marcas || marcas.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="4">
                <div class="empty-state">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                    <p>No hay marcas registradas.</p>
                </div>
            </td></tr>`;
        return;
    }
    tbody.innerHTML = marcas.map((m, i) => `
        <tr>
            <td style="color:var(--muted);font-size:0.8rem">${i + 1}</td>
            <td><strong>${m.nombre}</strong></td>
            <td>
                <span class="badge ${m.estado ? 'badge-active' : 'badge-inactive'}">
                    ${m.estado ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td>
                <div class="td-actions">
                    <button class="btn btn-edit btn-sm" onclick="openEdit(${m.id}, '${m.nombre}', ${m.estado ? 1 : 0})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="openDelete(${m.id}, '${m.nombre}')">Eliminar</button>
                </div>
            </td>
        </tr>
    `).join('');
}

// ── MODALES ──
function openModal(mode) {
    editingId = null;
    document.getElementById('modal-title').textContent = 'Nueva Marca';
    document.getElementById('field-nombre').value  = '';
    document.getElementById('field-estado').value  = '1';
    document.getElementById('modal-form').classList.add('open');
}

function openEdit(id, nombre, estado) {
    editingId = id;
    document.getElementById('modal-title').textContent = 'Editar Marca';
    document.getElementById('field-nombre').value = nombre;
    document.getElementById('field-estado').value = estado;
    document.getElementById('modal-form').classList.add('open');
}

function closeModal() {
    document.getElementById('modal-form').classList.remove('open');
}

function openDelete(id, nombre) {
    deletingId = id;
    document.getElementById('delete-nombre').textContent = nombre;
    document.getElementById('modal-delete').classList.add('open');
}

function closeDeleteModal() {
    document.getElementById('modal-delete').classList.remove('open');
}

// ── GUARDAR ──
async function saveMarca() {
    const nombre = document.getElementById('field-nombre').value.trim();
    const estado = document.getElementById('field-estado').value === '1';

    if (!nombre) { showAlert('El nombre es obligatorio.', 'error'); return; }

    const btn = document.getElementById('btn-save');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Guardando...';

    try {
        const url    = editingId ? `${API}/${editingId}` : API;
        const method = editingId ? 'PUT' : 'POST';

        const res  = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ nombre, estado }),
        });
        const json = await res.json();

        if (!res.ok) throw new Error(json.message || 'Error al guardar.');

        showAlert(json.message || 'Guardado correctamente.', 'success');
        closeModal();
        loadMarcas();
    } catch (e) {
        showAlert(e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Guardar';
    }
}

// ── ELIMINAR ──
async function confirmDelete() {
    try {
        const res  = await fetch(`${API}/${deletingId}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Error al eliminar.');
        showAlert(json.message || 'Eliminada correctamente.', 'success');
        closeDeleteModal();
        loadMarcas();
    } catch (e) {
        showAlert(e.message, 'error');
        closeDeleteModal();
    }
}

// ── ALERT ──
function showAlert(msg, type) {
    const div = document.getElementById('alert-container');
    div.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
    setTimeout(() => { div.innerHTML = ''; }, 4000);
}

// ── CERRAR MODAL AL CLICK FUERA ──
document.getElementById('modal-form').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
document.getElementById('modal-delete').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

loadMarcas();
</script>
@endpush
