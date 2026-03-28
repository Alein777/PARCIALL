@extends('layouts.app')

@section('title', 'Proveedores — GDA Store')

@section('content')

<div class="page-header">
    <h1>
        <span>Catálogo</span>
        Proveedores
    </h1>
    <button class="btn btn-primary" onclick="openModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nuevo Proveedor
    </button>
</div>

<div id="alert-container"></div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="proveedores-tbody">
            <tr class="loading-row">
                <td colspan="5">
                    <span class="spinner"></span>
                    <span style="margin-left:0.75rem">Cargando proveedores...</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- Modal Crear / Editar --}}
<div class="modal-overlay" id="modal-form">
    <div class="modal">
        <h2 id="modal-title">Nuevo Proveedor</h2>
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" id="field-nombre" placeholder="Nombre del proveedor">
        </div>
        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" id="field-telefono" placeholder="Ej: 7890-1234">
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
            <button class="btn btn-primary" id="btn-save" onclick="saveProveedor()">Guardar</button>
        </div>
    </div>
</div>

{{-- Modal Confirmar Eliminar --}}
<div class="modal-overlay" id="modal-delete">
    <div class="modal">
        <h2>¿Eliminar proveedor?</h2>
        <p style="color:var(--muted); margin-bottom:1.5rem; font-size:0.9rem;">
            Esta acción eliminará a <strong id="delete-nombre" style="color:var(--text)"></strong>. ¿Estás segura?
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
const API = '/api/proveedores';
let editingId = null;
let deletingId = null;

async function loadProveedores() {
    try {
        const res  = await fetch(API);
        const json = await res.json();
        renderTable(json.data);
    } catch (e) {
        showAlert('Error al conectar con la API.', 'error');
        document.getElementById('proveedores-tbody').innerHTML =
            `<tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--muted)">Sin datos</td></tr>`;
    }
}

function renderTable(proveedores) {
    const tbody = document.getElementById('proveedores-tbody');
    if (!proveedores || proveedores.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="5">
                <div class="empty-state">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <p>No hay proveedores registrados.</p>
                </div>
            </td></tr>`;
        return;
    }
    tbody.innerHTML = proveedores.map((p, i) => `
        <tr>
            <td style="color:var(--muted);font-size:0.8rem">${i + 1}</td>
            <td><strong>${p.nombre}</strong></td>
            <td style="color:var(--muted)">${p.telefono ?? '—'}</td>
            <td>
                <span class="badge ${p.estado ? 'badge-active' : 'badge-inactive'}">
                    ${p.estado ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td>
                <div class="td-actions">
                    <button class="btn btn-edit btn-sm" onclick="openEdit(${p.id}, '${p.nombre}', '${p.telefono ?? ''}', ${p.estado ? 1 : 0})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="openDelete(${p.id}, '${p.nombre}')">Eliminar</button>
                </div>
            </td>
        </tr>
    `).join('');
}

function openModal() {
    editingId = null;
    document.getElementById('modal-title').textContent = 'Nuevo Proveedor';
    document.getElementById('field-nombre').value    = '';
    document.getElementById('field-telefono').value  = '';
    document.getElementById('field-estado').value    = '1';
    document.getElementById('modal-form').classList.add('open');
}

function openEdit(id, nombre, telefono, estado) {
    editingId = id;
    document.getElementById('modal-title').textContent  = 'Editar Proveedor';
    document.getElementById('field-nombre').value    = nombre;
    document.getElementById('field-telefono').value  = telefono;
    document.getElementById('field-estado').value    = estado;
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

async function saveProveedor() {
    const nombre   = document.getElementById('field-nombre').value.trim();
    const telefono = document.getElementById('field-telefono').value.trim();
    const estado   = document.getElementById('field-estado').value === '1';

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
            body: JSON.stringify({ nombre, telefono, estado }),
        });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Error al guardar.');

        showAlert(json.message || 'Guardado correctamente.', 'success');
        closeModal();
        loadProveedores();
    } catch (e) {
        showAlert(e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Guardar';
    }
}

async function confirmDelete() {
    try {
        const res  = await fetch(`${API}/${deletingId}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
        const json = await res.json();
        if (!res.ok) throw new Error(json.message || 'Error al eliminar.');
        showAlert(json.message || 'Eliminado correctamente.', 'success');
        closeDeleteModal();
        loadProveedores();
    } catch (e) {
        showAlert(e.message, 'error');
        closeDeleteModal();
    }
}

function showAlert(msg, type) {
    const div = document.getElementById('alert-container');
    div.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
    setTimeout(() => { div.innerHTML = ''; }, 4000);
}

document.getElementById('modal-form').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
document.getElementById('modal-delete').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

loadProveedores();
</script>
@endpush
