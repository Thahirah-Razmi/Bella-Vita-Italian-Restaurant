import { Element } from "react-scroll";

const About = () => {
  return (
    <Element name="about" className="mb-20">
      <h1 className="text-4xl font-bold text-gray-800 mb-6">About Us</h1>
      <div className="space-y-2 text-gray-600">
        <p className="font-semibold">
          quite quite quite very plain discussions,
        </p>
        <p className="font-semibold">sometimes rather one of others offers</p>
        <p className="font-semibold">
          thoughts of events as well as other others.
        </p>
      </div>
    </Element>
  );
};

export default About;
