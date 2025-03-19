// Archivo: src/data/landingPageData.ts

export const venueTypes = [
    { 
      id: 'sports', 
      name: 'Sports Facilities', 
      description: 'Book courts, fields and training spaces',
      icon: 'M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5',
      image: '/images/sports-venue.jpg',
      examples: ['Padel Courts', 'Soccer Fields', 'Basketball Courts']
    },
    { 
      id: 'hotels', 
      name: 'Accommodations', 
      description: 'Find and book rooms across all our locations',
      icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a1 1 0 001-1v-7m-6 0h6',
      image: '/images/hotel-venue.jpg',
      examples: ['Luxury Suites', 'Double Rooms', 'Family Rooms']
    },
    { 
      id: 'restaurants', 
      name: 'Dining', 
      description: 'Reserve tables at our finest restaurants',
      icon: 'M3 3h18v18H3zM9 9h6l-1 5.5M8 9.5l-1-1 3-3 3 3 3-3 3 3-1 1M17 9.5H7M6 6h12M6 6h12',
      image: '/images/restaurant-venue.jpg',
      examples: ['Fine Dining', 'Private Booths', 'Event Spaces']
    }
  ];
  
  export const testimonials = [
    {
      id: 1,
      name: 'Sarah Johnson',
      role: 'Sports Club Manager',
      image: '/images/testimonial-1.jpg',
      content: 'This reservation system has completely transformed how we manage our facilities. The interface is intuitive and our customers love how easy it is to book online.',
      rating: 5
    },
    {
      id: 2,
      name: 'Michael Rodriguez',
      role: 'Hotel Director',
      image: '/images/testimonial-2.jpg',
      content: 'We\'ve seen a 40% increase in bookings since implementing this system. The analytics tools have helped us optimize our room availability and pricing.',
      rating: 5
    },
    {
      id: 3,
      name: 'Emma Wong',
      role: 'Restaurant Owner',
      image: '/images/testimonial-3.jpg',
      content: 'Managing reservations used to be a nightmare before we found this platform. Now everything is streamlined and we can focus on what we do best - serving great food!',
      rating: 4
    }
  ];
  
  export const features = [
    {
      title: 'Real-time Availability',
      description: 'See availability across all venues instantly, no more double bookings or confusion.',
      icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
    },
    {
      title: 'Smart Capacity Management',
      description: 'Optimize space utilization with intelligent capacity tracking and management.',
      icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
    },
    {
      title: 'Multi-location Support',
      description: 'Manage reservations across multiple branches and locations from one dashboard.',
      icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'
    },
    {
      title: 'Mobile-friendly Booking',
      description: 'Your customers can make reservations from any device, anywhere, anytime.',
      icon: 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'
    },
    {
      title: 'Customizable Settings',
      description: 'Adapt the system to your specific business needs with flexible configuration options.',
      icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
    },
    {
      title: 'Detailed Analytics',
      description: 'Gain insights into booking patterns and optimize your business with data-driven decisions.',
      icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
    }
  ];