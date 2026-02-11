import React, { useState, useEffect } from 'react';
import { FaChevronLeft, FaChevronRight } from 'react-icons/fa';
import api, { getImageUrl } from '../../utils/api';
import './HeroCarousel.css';

const HeroCarousel = () => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [carouselImages, setCarouselImages] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchCarouselImages();
  }, []);

  useEffect(() => {
    if (carouselImages.length > 1) {
      const interval = setInterval(() => {
        setCurrentIndex((prevIndex) => (prevIndex + 1) % carouselImages.length);
      }, 4000); // Auto-slide every 4 seconds

      return () => clearInterval(interval);
    }
  }, [carouselImages]);

  const fetchCarouselImages = async () => {
    try {
      const { data } = await api.get('/settings');
      if (data.carouselImages && data.carouselImages.length > 0) {
        setCarouselImages(data.carouselImages);
      }
    } catch (error) {
      console.error('Error fetching carousel images:', error);
    } finally {
      setLoading(false);
    }
  };

  const goToPrevious = () => {
    setCurrentIndex((prevIndex) => 
      prevIndex === 0 ? carouselImages.length - 1 : prevIndex - 1
    );
  };

  const goToNext = () => {
    setCurrentIndex((prevIndex) => (prevIndex + 1) % carouselImages.length);
  };

  const goToSlide = (index) => {
    setCurrentIndex(index);
  };

  if (loading) {
    return <div className="hero-carousel-skeleton"></div>;
  }

  if (!carouselImages || carouselImages.length === 0) {
    return null;
  }

  return (
    <div className="hero-carousel">
      <div className="carousel-container">
        {carouselImages.map((image, index) => (
          <div
            key={image._id}
            className={`carousel-slide ${index === currentIndex ? 'active' : ''}`}
          >
            <img
              src={getImageUrl(image._id)}
              alt={`Slide ${index + 1}`}
              className="carousel-image"
            />
          </div>
        ))}

        {carouselImages.length > 1 && (
          <>
            <button className="carousel-btn prev" onClick={goToPrevious}>
              <FaChevronLeft />
            </button>
            <button className="carousel-btn next" onClick={goToNext}>
              <FaChevronRight />
            </button>

            <div className="carousel-dots">
              {carouselImages.map((_, index) => (
                <button
                  key={index}
                  className={`dot ${index === currentIndex ? 'active' : ''}`}
                  onClick={() => goToSlide(index)}
                  aria-label={`Go to slide ${index + 1}`}
                />
              ))}
            </div>
          </>
        )}
      </div>
    </div>
  );
};

export default HeroCarousel;
