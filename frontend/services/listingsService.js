window.ListingsService = (function() {
    const listings = [
        {
            id: 1,
            title: 'Modern House - Center',
            location: 'Sarajevo, Center',
            price: 180000,
            beds: 3,
            baths: 2,
            area: 120,
            status: 'For Sale',
            image: 'assets/img/house1.jpg',
            description: 'Bright, spacious home in the heart of the city with a private garden and secure parking.',
            features: ['City center location', 'Private garden', 'Secure parking', 'Energy efficient', 'Open-plan living'],
            agent: { name: 'Adnan Hadzic', email: 'adnan@test.com', phone: '+387 61 234 567' }
        },
        {
            id: 2,
            title: 'Cozy Apartment - Ilidza',
            location: 'Ilidza, Sarajevo',
            price: 62000,
            beds: 1,
            baths: 1,
            area: 45,
            status: 'For Sale',
            image: 'assets/img/apartment2.jpg',
            description: 'Compact city apartment near parks and public transport, ideal for first-time buyers.',
            features: ['Near public transport', 'Low maintenance', 'Great investment', 'Balcony', 'Quiet street'],
            agent: { name: 'Emina Tahirovic', email: 'eminat@gmail.com', phone: '+387 61 555 111' }
        },
        {
            id: 3,
            title: 'Family Villa - Vogosca',
            location: 'Vogosca, Sarajevo',
            price: 310000,
            beds: 4,
            baths: 3,
            area: 220,
            status: 'For Sale',
            image: 'assets/img/villa3.jpg',
            description: 'Premium family villa with panoramic views, large terrace, and landscaped yard.',
            features: ['Panoramic views', 'Large terrace', 'Landscaped yard', 'Smart home ready', 'Garage'],
            agent: { name: 'Agent Smith', email: 'agent1@gmail.com', phone: '+387 61 333 444' }
        },
        {
            id: 4,
            title: 'Riverside Loft - Grbavica',
            location: 'Grbavica, Sarajevo',
            price: 98000,
            beds: 2,
            baths: 1,
            area: 68,
            status: 'For Sale',
            image: 'assets/img/about.jpg',
            description: 'Stylish loft with high ceilings and bright interiors, steps from the river walk.',
            features: ['High ceilings', 'River access', 'Modern kitchen', 'Natural light', 'Bike storage'],
            agent: { name: 'Mirsad Hadzic', email: 'mirsad@gmail.com', phone: '+387 61 222 333' }
        },
        {
            id: 5,
            title: 'New Build Apartment - Stup',
            location: 'Stup, Sarajevo',
            price: 89000,
            beds: 2,
            baths: 1,
            area: 62,
            status: 'For Sale',
            image: 'assets/img/hero-bg.jpg',
            description: 'Newly built apartment with modern finishes and efficient layout in a growing area.',
            features: ['New build', 'Elevator', 'Energy efficient', 'Nearby shops', 'Parking included'],
            agent: { name: 'Amra Hadzic', email: 'amrahadzic@gmail.com', phone: '+387 61 777 888' }
        },
        {
            id: 6,
            title: 'Green Garden Home - Ilidza',
            location: 'Ilidza, Sarajevo',
            price: 145000,
            beds: 3,
            baths: 2,
            area: 140,
            status: 'For Sale',
            image: 'assets/img/house1.jpg',
            description: 'Family home with a large backyard, perfect for outdoor living and weekend gatherings.',
            features: ['Large backyard', 'Family friendly', 'Renovated interior', 'Near schools', 'Storage room'],
            agent: { name: 'Second Admin', email: 'admin2@estatevision.com', phone: '+387 61 444 999' }
        },
        {
            id: 7,
            title: 'City Studio - Marijin Dvor',
            location: 'Marijin Dvor, Sarajevo',
            price: 52000,
            beds: 1,
            baths: 1,
            area: 35,
            status: 'For Sale',
            image: 'assets/img/apartment2.jpg',
            description: 'Compact studio in a premium location, ideal for young professionals.',
            features: ['Prime location', 'Easy commute', 'Furnished', 'Secure entry', 'Low fees'],
            agent: { name: 'Agent Marko', email: 'agent2@gmail.com', phone: '+387 61 555 666' }
        }
    ];

    function getAll() {
        return listings.slice();
    }

    function getById(id) {
        const numericId = parseInt(id, 10);
        return listings.find(item => item.id === numericId) || null;
    }

    return { getAll: getAll, getById: getById };
})();
