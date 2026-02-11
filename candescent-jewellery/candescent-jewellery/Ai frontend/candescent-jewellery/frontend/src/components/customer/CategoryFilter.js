import React, { useState, useEffect } from 'react';
import api from '../../utils/api';
import './CategoryFilter.css';

const CategoryFilter = ({ selectedCategory, onCategoryChange }) => {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchCategories();
  }, []);

  const fetchCategories = async () => {
    try {
      const { data } = await api.get('/products/categories/all');
      setCategories(data);
    } catch (error) {
      console.error('Error fetching categories:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="category-filter">
        <h3>Categories</h3>
        <div className="category-skeleton"></div>
      </div>
    );
  }

  return (
    <div className="category-filter">
      <h3>Categories</h3>
      <div className="category-list">
        <button
          className={`category-btn ${selectedCategory === 'All' ? 'active' : ''}`}
          onClick={() => onCategoryChange('All')}
        >
          All Products
        </button>
        {categories.map((category) => (
          <button
            key={category}
            className={`category-btn ${selectedCategory === category ? 'active' : ''}`}
            onClick={() => onCategoryChange(category)}
          >
            {category}
          </button>
        ))}
      </div>
    </div>
  );
};

export default CategoryFilter;
