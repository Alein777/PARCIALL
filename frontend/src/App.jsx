import { useState } from 'react'
import Marcas from './pages/Marcas'
import Categorias from './pages/Categorias'
import Proveedores from './pages/Proveedores'
import './App.css'

function App() {
  const [page, setPage] = useState('marcas')

  return (
    <div className="app">
      <nav className="navbar">
        <a className="nav-brand" onClick={() => setPage('marcas')}>
          GDA<span>Store</span>
        </a>
        <div className="nav-links">
          <button className={page === 'marcas' ? 'active' : ''} onClick={() => setPage('marcas')}>Marcas</button>
          <button className={page === 'categorias' ? 'active' : ''} onClick={() => setPage('categorias')}>Categorías</button>
          <button className={page === 'proveedores' ? 'active' : ''} onClick={() => setPage('proveedores')}>Proveedores</button>
        </div>
      </nav>

      <main>
        {page === 'marcas'      && <Marcas />}
        {page === 'categorias'  && <Categorias />}
        {page === 'proveedores' && <Proveedores />}
      </main>
    </div>
  )
}

export default App