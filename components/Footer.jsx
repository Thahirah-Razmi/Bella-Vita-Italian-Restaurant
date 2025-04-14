const Footer = () => {
  return (
    <footer className="mt-20 border-t border-gray-200">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid md:grid-cols-3 gap-8 py-12">
          <div>
            <h4 className="text-lg font-semibold text-amber-600 mb-4">
              Contact Info
            </h4>
            <p className="text-gray-600">
              123 Gourmet Street
              <br />
              Culinary District
              <br />
              Tel: +46 123 456 789
              <br />
              Email: info@restaurant.com
            </p>
          </div>

          <div>
            <h4 className="text-lg font-semibold text-amber-600 mb-4">
              Opening Hours
            </h4>
            <p className="text-gray-600">
              Monday-Friday: 11:00 - 23:00
              <br />
              Saturday: 10:00 - 00:00
              <br />
              Sunday: 12:00 - 22:00
            </p>
          </div>

          <div>
            <h4 className="text-lg font-semibold text-amber-600 mb-4">
              Quick Links
            </h4>
            <div className="space-y-2">
              {[
                "Special Menu",
                "Gift Cards",
                "Reservations",
                "Private Dining",
              ].map((link) => (
                <a
                  key={link}
                  href="#"
                  className="block text-gray-600 hover:text-amber-600 transition-colors"
                >
                  {link}
                </a>
              ))}
            </div>
          </div>
        </div>

        <div className="py-6 text-center text-gray-500 border-t border-gray-200">
          <p>&copy; 2005-2023 Restaurant Name. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
