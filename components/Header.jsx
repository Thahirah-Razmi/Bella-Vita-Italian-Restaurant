import { Link } from "react-scroll";

const Header = () => {
  return (
    <header className="fixed w-full top-0 bg-white shadow-sm z-50">
      <nav className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center h-16">
          <div className="text-2xl font-bold text-amber-600">Restaurant</div>
          <div className="hidden md:flex space-x-8">
            {["About", "Menu", "Rooms", "Tables", "Reviews", "Contact"].map(
              (item) => (
                <Link
                  key={item}
                  to={item.toLowerCase()}
                  spy={true}
                  smooth={true}
                  offset={-70}
                  duration={500}
                  className="text-gray-600 hover:text-amber-600 transition-colors duration-200 cursor-pointer"
                >
                  {item}
                </Link>
              )
            )}
          </div>
        </div>
      </nav>
    </header>
  );
};

export default Header;
