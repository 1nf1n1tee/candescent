import React, { useState, useEffect } from 'react';
import Navbar from '../../components/customer/Navbar';
import HeroCarousel from '../../components/customer/HeroCarousel';
import CategoryFilter from '../../components/customer/CategoryFilter';
import ProductGrid from '../../components/customer/ProductGrid';
import api from '../../utils/api';
import './HomePage.css';

const HomePage = () => {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedCategory, setSelectedCategory] = useState('All');

  useEffect(() => {
    fetchProducts();
  }, [selectedCategory]);

  const fetchProducts = async () => {
    try {
      setLoading(true);
      const params = selectedCategory !== 'All' ? `?category=${selectedCategory}` : '';
      const { data } = await api.get(`/products${params}`);
      setProducts(data);
    } catch (error) {
      console.error('Error fetching products:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="home-page">
      <Navbar />
      <HeroCarousel />
      <div className="container">
        <div className="home-content">
          <aside className="sidebar-section">
            <CategoryFilter
              selectedCategory={selectedCategory}
              onCategoryChange={setSelectedCategory}
            />
          </aside>
          <main className="products-section">
            <div className="products-header">
              <h2>
                {selectedCategory === 'All' ? 'All Products' : selectedCategory}
              </h2>
              <p className="products-count">
                {products.length} {products.length === 1 ? 'product' : 'products'} found
              </p>
            </div>
            <ProductGrid products={products} loading={loading} />
          </main>
        </div>
      </div>
    </div>
  );
};

export default HomePage;
