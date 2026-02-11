import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { CartProvider } from './contexts/CartContext';
import { AuthProvider } from './contexts/AuthContext';

// Customer Pages
import HomePage from './pages/customer/HomePage';
import ProductDetailPage from './pages/customer/ProductDetailPage';
import CartPage from './pages/customer/CartPage';
import CheckoutPage from './pages/customer/CheckoutPage';

// Admin Pages
import AdminLogin from './pages/admin/AdminLogin';
import AdminRegister from './pages/admin/AdminRegister';
import AdminDashboard from './pages/admin/AdminDashboard';
import ProtectedRoute from './components/common/ProtectedRoute';

import './styles/global.css';

function App() {
  return (
    <AuthProvider>
      <CartProvider>
        <Router>
          <div className="App">
            <Routes>
              {/* Customer Routes */}
              <Route path="/" element={<HomePage />} />
              <Route path="/product/:id" element={<ProductDetailPage />} />
              <Route path="/cart" element={<CartPage />} />
              <Route path="/checkout" element={<CheckoutPage />} />

              {/* Admin Routes */}
              <Route path="/admin_candescent_store" element={<AdminLogin />} />
              <Route path="/admin_candescent_store/register" element={<AdminRegister />} />
              <Route
                path="/admin_candescent_store/dashboard"
                element={
                  <ProtectedRoute>
                    <AdminDashboard />
                  </ProtectedRoute>
                }
              />
            </Routes>
            <ToastContainer
              position="top-right"
              autoClose={3000}
              hideProgressBar={false}
              newestOnTop
              closeOnClick
              rtl={false}
              pauseOnFocusLoss
              draggable
              pauseOnHover
            />
          </div>
        </Router>
      </CartProvider>
    </AuthProvider>
  );
}

export default App;
