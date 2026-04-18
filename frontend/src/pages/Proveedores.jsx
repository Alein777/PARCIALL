import { useState, useEffect } from 'react'
import axios from 'axios'

const API = 'http://127.0.0.1:8000/api/proveedores'

export default function Proveedores() {
  const [proveedores, setProveedores] = useState([])
  const [loading, setLoading]         = useState(true)
  const [alert, setAlert]             = useState(null)
  const [showForm, setShowForm]       = useState(false)
  const [showDelete, setShowDelete]   = useState(false)
  const [editingId, setEditingId]     = useState(null)
  const [deletingId, setDeletingId]   = useState(null)
  const [deleteNombre, setDeleteNombre] = useState('')
  const [saving, setSaving]           = useState(false)
  const [form, setForm]               = useState({ nombre: '', telefono: '', estado: '1' })

  useEffect(() => { loadProveedores() }, [])

  async function loadProveedores() {
    setLoading(true)
    try {
      const res = await axios.get(API)
      setProveedores(res.data.data)
    } catch {
      showAlert('Error al conectar con la API.', 'error')
    } finally {
      setLoading(false)
    }
  }

  function showAlert(msg, type) {
    setAlert({ msg, type })
    setTimeout(() => setAlert(null), 4000)
  }

  function openCreate() {
    setEditingId(null)
    setForm({ nombre: '', telefono: '', estado: '1' })
    setShowForm(true)
  }

  function openEdit(p) {
    setEditingId(p.id)
    setForm({ nombre: p.nombre, telefono: p.telefono || '', estado: p.estado ? '1' : '0' })
    setShowForm(true)
  }

  function openDelete(p) {
    setDeletingId(p.id)
    setDeleteNombre(p.nombre)
    setShowDelete(true)
  }

  async function save() {
    if (!form.nombre.trim()) { showAlert('El nombre es obligatorio.', 'error'); return }
    setSaving(true)
    try {
      const data = { nombre: form.nombre, telefono: form.telefono, estado: form.estado === '1' }
      if (editingId) {
        const res = await axios.put(`${API}/${editingId}`, data)
        showAlert(res.data.message, 'success')
      } else {
        const res = await axios.post(API, data)
        showAlert(res.data.message, 'success')
      }
      setShowForm(false)
      loadProveedores()
    } catch (e) {
      showAlert(e.response?.data?.message || 'Error al guardar.', 'error')
    } finally {
      setSaving(false)
    }
  }

  async function confirmDelete() {
    try {
      const res = await axios.delete(`${API}/${deletingId}`)
      showAlert(res.data.message, 'success')
      setShowDelete(false)
      loadProveedores()
    } catch (e) {
      showAlert(e.response?.data?.message || 'Error al eliminar.', 'error')
      setShowDelete(false)
    }
  }

  return (
    <div>
      <div className="page-header">
        <h1><span>Catálogo</span>Proveedores</h1>
        <button className="btn btn-primary" onClick={openCreate}>+ Nuevo Proveedor</button>
      </div>

      {alert && <div className={`alert alert-${alert.type}`}>{alert.msg}</div>}

      <div className="table-wrap">
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
          <tbody>
            {loading ? (
              <tr><td colSpan="5" className="loading-cell"><span className="spinner"></span>Cargando...</td></tr>
            ) : proveedores.length === 0 ? (
              <tr><td colSpan="5"><div className="empty-state">No hay proveedores registrados.</div></td></tr>
            ) : proveedores.map((p, i) => (
              <tr key={p.id}>
                <td className="muted">{i + 1}</td>
                <td><strong>{p.nombre}</strong></td>
                <td className="muted">{p.telefono || '—'}</td>
                <td><span className={`badge ${p.estado ? 'badge-active' : 'badge-inactive'}`}>{p.estado ? 'Activo' : 'Inactivo'}</span></td>
                <td>
                  <div className="td-actions">
                    <button className="btn btn-edit btn-sm" onClick={() => openEdit(p)}>Editar</button>
                    <button className="btn btn-danger btn-sm" onClick={() => openDelete(p)}>Eliminar</button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {showForm && (
        <div className="modal-overlay" onClick={e => e.target === e.currentTarget && setShowForm(false)}>
          <div className="modal">
            <h2>{editingId ? 'Editar Proveedor' : 'Nuevo Proveedor'}</h2>
            <div className="form-group">
              <label>Nombre</label>
              <input value={form.nombre} onChange={e => setForm({...form, nombre: e.target.value})} placeholder="Nombre del proveedor" />
            </div>
            <div className="form-group">
              <label>Teléfono</label>
              <input value={form.telefono} onChange={e => setForm({...form, telefono: e.target.value})} placeholder="Ej: 7890-1234" />
            </div>
            <div className="form-group">
              <label>Estado</label>
              <select value={form.estado} onChange={e => setForm({...form, estado: e.target.value})}>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
            <div className="form-actions">
              <button className="btn-cancel" onClick={() => setShowForm(false)}>Cancelar</button>
              <button className="btn btn-primary" onClick={save} disabled={saving}>
                {saving ? <><span className="spinner"></span>Guardando...</> : 'Guardar'}
              </button>
            </div>
          </div>
        </div>
      )}

      {showDelete && (
        <div className="modal-overlay" onClick={e => e.target === e.currentTarget && setShowDelete(false)}>
          <div className="modal">
            <h2>¿Eliminar proveedor?</h2>
            <p style={{color:'var(--muted)', marginBottom:'1.5rem', fontSize:'0.9rem'}}>
              Esta acción eliminará a <strong style={{color:'var(--text)'}}>{deleteNombre}</strong>. ¿Estás segura?
            </p>
            <div className="form-actions">
              <button className="btn-cancel" onClick={() => setShowDelete(false)}>Cancelar</button>
              <button className="btn btn-danger" onClick={confirmDelete}>Sí, eliminar</button>
            </div>
          </div>
        </div>
      )}
    </div>
  )
}
