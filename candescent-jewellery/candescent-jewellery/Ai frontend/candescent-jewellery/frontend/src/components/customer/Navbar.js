import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { FaShoppingCart, FaBars, FaTimes } from 'react-icons/fa';
import { useCart } from '../../contexts/CartContext';
import api, { getImageUrl } from '../../utils/api';
import './Navbar.css';

const Navbar = () => {
  const { getCartCount } = useCart();
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);
  const [logo, setLogo] = useState(null);
  const [storeName, setStoreName] = useState('Candescént');

  React.useEffect(() => {
    fetchSettings();
  }, []);

  const fetchSettings = async () => {
    try {
      const { data } = await api.get('/settings');
      if (data.logo) {
        setLogo(data.logo);
      }
      if (data.storeName) {
        setStoreName(data.storeName);
      }
    } catch (error) {
      console.error('Error fetching settings:', error);
    }
  };

  const toggleSidebar = () => {
    setIsSidebarOpen(!isSidebarOpen);
  };

  return (
    <>
      <nav className="navbar">
        <div className="container">
          <div className="navbar-content">
            <button className="menu-toggle" onClick={toggleSidebar}>
              <FaBars />
            </button>

            <Link to="/" className="navbar-brand">
              {logo ? (
                <img 
                  src={getImageUrl(logo._id)} 
                  alt={storeName} 
                  className="navbar-logo"
                />
              ) : (
                <h1>{storeName}</h1>
              )}
            </Link>

            <Link to="/cart" className="cart-icon-link">
              <FaShoppingCart />
              {getCartCount() > 0 && (
                <span className="cart-count">{getCartCount()}</span>
              )}
            </Link>
          </div>
        </div>
      </nav>

      {/* Mobile Sidebar */}
      <div className={`sidebar-overlay ${isSidebarOpen ? 'active' : ''}`} onClick={toggleSidebar}></div>
      <div className={`sidebar ${isSidebarOpen ? 'open' : ''}`}>
        <div className="sidebar-header">
          <h2>{storeName}</h2>
          <button className="close-sidebar" onClick={toggleSidebar}>
            <FaTimes />
          </button>
        </div>
        <nav className="sidebar-nav">
          <Link to="/" onClick={toggleSidebar}>Home</Link>
          <Link to="/cart" onClick={toggleSidebar}>Shopping Cart</Link>
        </nav>
      </div>
    </>
  );
};

export default Navbar;
