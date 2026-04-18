import { useState, useEffect } from 'react'
import axios from 'axios'

const API = 'http://127.0.0.1:8000/api/marcas'

export default function Marcas() {
  const [marcas, setMarcas]       = useState([])
  const [loading, setLoading]     = useState(true)
  const [alert, setAlert]         = useState(null)
  const [showForm, setShowForm]   = useState(false)
  const [showDelete, setShowDelete] = useState(false)
  const [editingId, setEditingId] = useState(null)
  const [deletingId, setDeletingId] = useState(null)
  const [deleteNombre, setDeleteNombre] = useState('')
  const [saving, setSaving]       = useState(false)
  const [form, setForm]           = useState({ nombre: '', estado: '1' })

  useEffect(() => { loadMarcas() }, [])

  async function loadMarcas() {
    setLoading(true)
    try {
      const res = await axios.get(API)
      setMarcas(res.data.data)
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
    setForm({ nombre: '', estado: '1' })
    setShowForm(true)
  }

  function openEdit(m) {
    setEditingId(m.id)
    setForm({ nombre: m.nombre, estado: m.estado ? '1' : '0' })
    setShowForm(true)
  }

  function openDelete(m) {
    setDeletingId(m.id)
    setDeleteNombre(m.nombre)
    setShowDelete(true)
  }

  async function save() {
    if (!form.nombre.trim()) { showAlert('El nombre es obligatorio.', 'error'); return }
    setSaving(true)
    try {
      const data = { nombre: form.nombre, estado: form.estado === '1' }
      if (editingId) {
        const res = await axios.put(`${API}/${editingId}`, data)
        showAlert(res.data.message, 'success')
      } else {
        const res = await axios.post(API, data)
        showAlert(res.data.message, 'success')
      }
      setShowForm(false)
      loadMarcas()
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
      loadMarcas()
    } catch (e) {
      showAlert(e.response?.data?.message || 'Error al eliminar.', 'error')
      setShowDelete(false)
    }
  }

  return (
    <div>
      <div className="page-header">
        <h1><span>Catálogo</span>Marcas</h1>
        <button className="btn btn-primary" onClick={openCreate}>+ Nueva Marca</button>
      </div>

      {alert && <div className={`alert alert-${alert.type}`}>{alert.msg}</div>}

      <div className="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Nombre</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {loading ? (
              <tr><td colSpan="4" className="loading-cell"><span className="spinner"></span>Cargando...</td></tr>
            ) : marcas.length === 0 ? (
              <tr><td colSpan="4"><div className="empty-state">No hay marcas registradas.</div></td></tr>
            ) : marcas.map((m, i) => (
              <tr key={m.id}>
                <td className="muted">{i + 1}</td>
                <td><strong>{m.nombre}</strong></td>
                <td><span className={`badge ${m.estado ? 'badge-active' : 'badge-inactive'}`}>{m.estado ? 'Activo' : 'Inactivo'}</span></td>
                <td>
                  <div className="td-actions">
                    <button className="btn btn-edit btn-sm" onClick={() => openEdit(m)}>Editar</button>
                    <button className="btn btn-danger btn-sm" onClick={() => openDelete(m)}>Eliminar</button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Modal Crear/Editar */}
      {showForm && (
        <div className="modal-overlay" onClick={e => e.target === e.currentTarget && setShowForm(false)}>
          <div className="modal">
            <h2>{editingId ? 'Editar Marca' : 'Nueva Marca'}</h2>
            <div className="form-group">
              <label>Nombre</label>
              <input value={form.nombre} onChange={e => setForm({...form, nombre: e.target.value})} placeholder="Ej: Nike, Adidas..." />
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

      {/* Modal Eliminar */}
      {showDelete && (
        <div className="modal-overlay" onClick={e => e.target === e.currentTarget && setShowDelete(false)}>
          <div className="modal">
            <h2>¿Eliminar marca?</h2>
            <p style={{color:'var(--muted)', marginBottom:'1.5rem', fontSize:'0.9rem'}}>
              Esta acción eliminará <strong style={{color:'var(--text)'}}>{deleteNombre}</strong>. ¿Estás segura?
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
