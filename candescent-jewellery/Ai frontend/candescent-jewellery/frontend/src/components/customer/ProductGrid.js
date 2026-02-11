import React from 'react';
import ProductCard from './ProductCard';
import './ProductGrid.css';

const ProductGrid = ({ products, loading }) => {
  if (loading) {
    return (
      <div className="product-grid">
        {[...Array(6)].map((_, index) => (
          <div key={index} className="product-card-skeleton"></div>
        ))}
      </div>
    );
  }

  if (!products || products.length === 0) {
    return (
      <div className="no-products">
        <p>No products found</p>
      </div>
    );
  }

  return (
    <div className="product-grid">
      {products.map((product) => (
        <ProductCard key={product._id} product={product} />
      ))}
    </div>
  );
};

export default ProductGrid;
