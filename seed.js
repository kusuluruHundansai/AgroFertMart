const mongoose = require('mongoose');
const dotenv = require('dotenv');
const Product = require('./models/Product');
const User = require('./models/User');

dotenv.config();

const products = [

  // ─── FERTILIZERS ────────────────────────────────────────────────────
  {
    name: "Premium Urea Fertilizer",
    description: "High nitrogen content (46%) fertilizer for rapid plant growth and deep green foliage. Industrial grade quality for maximum yield.",
    price: 450,
    category: "Fertilizer",
    image_url: "/images/urea.jpg",
    badge: "POPULAR"
  },
  {
    name: "Organic Vermicompost",
    description: "Enriched with earthworms and organic matter to improve soil structure, microbial activity, and water retention naturally.",
    price: 320,
    category: "Fertilizer",
    image_url: "/images/vermicompost.jpg"
  },
  {
    name: "NPK 19-19-19",
    description: "Balanced water-soluble fertilizer containing Nitrogen, Phosphorus, and Potassium for all development stages of crops.",
    price: 280,
    category: "Fertilizer",
    image_url: "/images/npk.jpg",
    badge: "POPULAR"
  },
  {
    name: "Muriate of Potash (MOP)",
    description: "Essential source of Potassium for root development and disease resistance. Improves crop quality and shelf life.",
    price: 520,
    category: "Fertilizer",
    image_url: "/images/potash.jpg"
  },
  {
    name: "Zinc Sulphate Heptahydrate",
    description: "High-purity micro-nutrient for correcting Zinc deficiency in paddy and fruit crops.",
    price: 180,
    category: "Fertilizer",
    image_url: "/images/zincsulphate.jpg"
  },
  {
    name: "Di-Ammonium Phosphate (DAP)",
    description: "Premium source of Phosphorus and Nitrogen. Enhances root development and gives crops a strong early start.",
    price: 680,
    category: "Fertilizer",
    image_url: "/images/dap.jpg",
    badge: "POPULAR"
  },
  {
    name: "Ammonium Sulphate",
    description: "Provides both Nitrogen and Sulphur for healthy crop growth. Ideal for alkaline soils and paddy fields.",
    price: 370,
    category: "Fertilizer",
    image_url: "/images/amsulfate.jpg"
  },
  {
    name: "Single Super Phosphate (SSP)",
    description: "Rich in Phosphorus and Sulphur. Boosts root growth, flower formation and grain filling.",
    price: 290,
    category: "Fertilizer",
    image_url: "/images/superphosphate.jpg"
  },
  {
    name: "Organic Bio-Fertilizer",
    description: "Beneficial bacteria and fungi blend that fixes atmospheric nitrogen and solubilizes phosphorus for organic farms.",
    price: 210,
    category: "Fertilizer",
    image_url: "/images/biofertilizer.jpg",
    badge: "NEW"
  },
  {
    name: "Seaweed & Sea Bird Guano Mix",
    description: "100% natural marine-based plant booster — rich in trace elements, growth hormones, and amino acids.",
    price: 550,
    category: "Fertilizer",
    image_url: "/images/seabird.jpg",
    badge: "NEW"
  },
  {
    name: "Organic Cow Dung Compost",
    description: "Well-decomposed farmyard manure that improves soil aeration, water holding capacity, and microbial activity.",
    price: 150,
    category: "Fertilizer",
    image_url: "/images/cow.jpg"
  },
  {
    name: "Micro-Nutrient Mixture",
    description: "Chelated mix of Boron, Copper, Iron, Manganese, Molybdenum, and Zinc for combating deficiencies.",
    price: 340,
    category: "Fertilizer",
    image_url: "/images/micro.jpg"
  },

  // ─── PESTICIDES ──────────────────────────────────────────────────────
  {
    name: "Bio-Pesticide Neem Oil",
    description: "Pure cold-pressed neem oil. Natural pest repellent effectively controls mites, aphids, and whiteflies without chemicals.",
    price: 150,
    category: "Pesticide",
    image_url: "/images/neem.jpg",
    badge: "POPULAR"
  },
  {
    name: "Fipronil Insecticide",
    description: "Broad-spectrum insecticide for controlling termites, ants, and beetles. Long-lasting protection for your fields.",
    price: 650,
    category: "Pesticide",
    image_url: "/images/fipronil.jpg"
  },
  {
    name: "Propiconazole Fungicide",
    description: "Systemic fungicide for treating powdery mildew and rust in cereals and ornamentals.",
    price: 420,
    category: "Pesticide",
    image_url: "/images/fungi.jpg"
  },
  {
    name: "Imidacloprid Insecticide",
    description: "Systemic neonicotinoid used for sucking pests like BPH, thrips, and leafhoppers on all crops.",
    price: 580,
    category: "Pesticide",
    image_url: "/images/imidacloprid.jpg",
    badge: "POPULAR"
  },
  {
    name: "Chlorpyrifos EC",
    description: "Broad-spectrum organophosphate insecticide for soil and foliar applications on rice, cotton, and sugarcane.",
    price: 310,
    category: "Pesticide",
    image_url: "/images/chlorpyrifos.jpg"
  },
  {
    name: "Cypermethrin Spray",
    description: "Fast-knockdown pyrethroid insecticide for caterpillars, bollworms, and armyworms on crops.",
    price: 260,
    category: "Pesticide",
    image_url: "/images/cypermethrin.jpg"
  },
  {
    name: "Carbendazim Fungicide",
    description: "Systemic fungicide against smut, blight, and anthracnose. Broad-spectrum disease control.",
    price: 390,
    category: "Pesticide",
    image_url: "/images/carbendazim.jpg"
  },
  {
    name: "Malathion Insecticide",
    description: "Effective organophosphate against a wide range of sucking and chewing insects. Safe post-harvest storage protection.",
    price: 220,
    category: "Pesticide",
    image_url: "/images/malathion.jpg"
  },
  {
    name: "Deltamethrin Insecticide",
    description: "Powerful synthetic pyrethroid for fly, mosquito, and stored-grain pest control. Long residual action.",
    price: 480,
    category: "Pesticide",
    image_url: "/images/deltamethrin.jpg"
  },
  {
    name: "Herbal Insect Repellent Spray",
    description: "Plant-extract based bio-repellent safe for use on vegetables and fruits. Zero chemical residues.",
    price: 190,
    category: "Pesticide",
    image_url: "/images/herbal.jpg",
    badge: "NEW"
  },
  {
    name: "Carbaryl Dust Pesticide",
    description: "Ready-to-use wettable powder for controlling mites, caterpillars, and beetles across multiple crops.",
    price: 175,
    category: "Pesticide",
    image_url: "/images/carbaryl.jpg"
  },
  {
    name: "Monocrotophos Insecticide",
    description: "Systemic organophosphate for managing sucking pests and borers on cotton, paddy, and vegetables.",
    price: 295,
    category: "Pesticide",
    image_url: "/images/monocrotophos.jpg"
  },

  // ─── EQUIPMENT ───────────────────────────────────────────────────────
  {
    name: "Heavy Duty Garden Hoe",
    description: "Forged carbon steel head with durable heat-treated wooden handle for efficient tilling and weeding.",
    price: 850,
    category: "Equipment",
    image_url: "/images/hoe.jpg"
  },
  {
    name: "Electric Power Sprayer",
    description: "16-liter capacity battery-operated sprayer with multi-nozzle set for uniform application of nutrients and pesticides.",
    price: 3200,
    category: "Equipment",
    image_url: "/images/sprayer.jpg",
    badge: "POPULAR"
  },
  {
    name: "Hand-operated Seeder",
    description: "Precision seeder for manual sowing. Adjustable depth and spacing for various crop varieties.",
    price: 1800,
    category: "Equipment",
    image_url: "/images/seeder.jpg"
  },
  {
    name: "Rotary Mini Tiller",
    description: "Compact petrol-powered soil tiller ideal for small and medium farms. 4-blade design for deep loosening.",
    price: 12500,
    category: "Equipment",
    image_url: "/images/tiller.jpg",
    badge: "NEW"
  },
  {
    name: "Steel Shovel & Spade",
    description: "Heavy-gauge galvanised steel with anti-slip ergonomic handle. Perfect for digging, lifting and mixing.",
    price: 640,
    category: "Equipment",
    image_url: "/images/shovel.jpg"
  },
  {
    name: "Garden Pruning Shears",
    description: "High-carbon bypass shears with non-stick blade coating. Precise clean cuts for branches up to 25mm.",
    price: 380,
    category: "Equipment",
    image_url: "/images/shears.jpg"
  },
  {
    name: "Drip Irrigation Kit",
    description: "Complete DIY drip system for 100 plants — includes main pipe, emitters, connectors & filter. Saves 60% water.",
    price: 2800,
    category: "Equipment",
    image_url: "/images/dripkit.jpg",
    badge: "POPULAR"
  },
  {
    name: "Watering Can (10L)",
    description: "Rust-proof galvanised steel watering can with long spout. Even water distribution for seedlings and pots.",
    price: 450,
    category: "Equipment",
    image_url: "/images/watering.jpg"
  },
  {
    name: "Seed Germination Tray",
    description: "50-cell professional seedling propagation tray. UV-stabilised, reusable and stackable.",
    price: 120,
    category: "Equipment",
    image_url: "/images/seedtray.jpg"
  },
  {
    name: "Rotary Weeder",
    description: "Ergonomic hand-push rotary weeder for row crops. Wide 200mm cutting width. No bending required.",
    price: 760,
    category: "Equipment",
    image_url: "/images/weeder.jpg"
  },
  {
    name: "Farm Trolley Cart",
    description: "Heavy-duty galvanised metal trolley with 200kg capacity and pneumatic wheels for farm transport.",
    price: 4200,
    category: "Equipment",
    image_url: "/images/trolley.jpg"
  },
  {
    name: "Grass Cutter / Sickle",
    description: "Tempered steel curved blade sickle for harvesting paddy, wheat, and cutting grass. Comfortable grip.",
    price: 290,
    category: "Equipment",
    image_url: "/images/cutter.jpg"
  },
  {
    name: "Farmer Safety Boots",
    description: "Waterproof rubber/PVC mid-calf boots. Anti-slip sole and reinforced toe cap for all-terrain protection.",
    price: 920,
    category: "Equipment",
    image_url: "/images/boots.jpg",
    badge: "NEW"
  },
  {
    name: "Chemical-Resistant Gloves",
    description: "Nitrile-coated heavy-duty gloves — puncture and chemical resistant. Essential PPE for pesticide application.",
    price: 180,
    category: "Equipment",
    image_url: "/images/gloves.jpg"
  },

  // ─── SEEDS ───────────────────────────────────────────────────────────
  {
    name: "High-Yield Tomato Seeds",
    description: "Elite F1 hybrid seeds optimised for high productivity and disease resistance in tropical climates.",
    price: 120,
    category: "Seed",
    image_url: "/images/fresh-tomatoes.png",
    badge: "POPULAR"
  },
  {
    name: "Hybrid Wheat Seeds (HD 2967)",
    description: "Popular high-yield wheat variety resistant to heat and yellow rust. High protein content.",
    price: 950,
    category: "Seed",
    image_url: "/images/premium-wheat.png"
  },
  {
    name: "African Bird Eye Chilli Seeds",
    description: "Extremely pungent and high-yielding chilli variety. Drought resistant and easy to maintain.",
    price: 75,
    category: "Seed",
    image_url: "/images/red-chilli.png"
  },
  {
    name: "Hybrid Sweet Corn Seeds",
    description: "Super-sweet variety with uniform cob size. 75-80 days maturity period. High market demand.",
    price: 240,
    category: "Seed",
    image_url: "https://images.unsplash.com/photo-1551754655-cd27e38d2076?q=80&w=800&auto=format&fit=crop",
    badge: "NEW"
  },
  {
    name: "Basmati Rice Seeds (Pusa 1121)",
    description: "Award-winning long-grain aromatic basmati variety with extra elongation on cooking. High export demand.",
    price: 780,
    category: "Seed",
    image_url: "https://images.unsplash.com/photo-1516684732162-798a0062be99?q=80&w=800&auto=format&fit=crop",
    badge: "POPULAR"
  },
  {
    name: "Okra / Bhendi Seeds (F1 Hybrid)",
    description: "Disease-resistant okra hybrid with thick dark green pods. Prolific bearer with 60-day harvest cycle.",
    price: 90,
    category: "Seed",
    image_url: "https://images.unsplash.com/photo-1567375698348-5d9d5ae99de0?q=80&w=800&auto=format&fit=crop"
  },
  {
    name: "Sunflower Seeds (High-oil NuSun)",
    description: "Mid-oleic open-pollinated sunflower seeds with 45% oil content and disease package resistance.",
    price: 310,
    category: "Seed",
    image_url: "https://images.unsplash.com/photo-1597714026720-8f74c62310ba?q=80&w=800&auto=format&fit=crop"
  },
  {
    name: "Smart Soil Moisture & pH Sensor",
    description: "Next-gen agricultural sensor with digital display. Monitors moisture and pH levels in real-time to optimize irrigation and soil health.",
    price: 3450,
    category: "Equipment",
    image_url: "/images/smart-soil-sensor.png",
    badge: "NEW"
  },
  {
    name: "Solar-Powered Irrigation Controller",
    description: "AI-ready solar controller for automatic drip irrigation. Weather-syncing technology saves water and effort.",
    price: 8900,
    category: "Equipment",
    image_url: "/images/solar-irrigation.png",
    badge: "NEW"
  },
  {
    name: "Eco-Friendly Solar Crop Dryer",
    description: "High-efficiency solar dryer for chilies, herbs, and grains. Reduces post-harvest loss while maintaining nutrients.",
    price: 15600,
    category: "Equipment",
    image_url: "https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?q=80&w=800&auto=format&fit=crop"
  },
  {
    name: "Botanical Organic Pesticide",
    description: "100% natural plant-based pesticide. Safely controls pests while being completely non-toxic to humans and bees.",
    price: 850,
    category: "Pesticide",
    image_url: "/images/botanical-pesticide.png",
    badge: "NEW"
  },
  {
    name: "Hybrid Gold Maize Seeds",
    description: "Premium hybrid corn seeds with 30% higher yield. Drought resistant and optimized for various soil types.",
    price: 1250,
    category: "Seed",
    image_url: "/images/hybrid-maize.png",
    badge: "POPULAR"
  }
];

const seedDB = async () => {
  try {
    await mongoose.connect(process.env.MONGODB_URI);
    console.log("✅ Connected to MongoDB for seeding...");

    // Clear existing products
    await Product.deleteMany({});
    console.log("🗑️  Cleared existing products.");

    // Insert new products
    const inserted = await Product.insertMany(products);
    console.log(`🚀 ${inserted.length} premium products added successfully!`);

    try {
      const adminByUsername = await User.findOne({ username: "admin" });
      const adminByEmail    = await User.findOne({ email: "admin@agrofertmart.com" });

      if (!adminByUsername && !adminByEmail) {
        const admin = new User({
          username: "admin",
          email:    "admin@agrofertmart.com",
          password: "adminpassword",
          role:     "admin"
        });
        await admin.save();
        console.log("👤 Admin user created (admin / adminpassword)");
      } else {
        console.log("ℹ️  Admin user already exists, skipping.");
      }
    } catch (adminErr) {
      console.log("⚠️  Could not create admin:", adminErr.message);
    }

    console.log("✨ Seeding complete!");
    process.exit();
  } catch (err) {
    console.error("❌ Seeding error:", err);
    process.exit(1);
  }
};

seedDB();
