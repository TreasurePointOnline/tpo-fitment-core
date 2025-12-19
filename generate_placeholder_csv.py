import pandas as pd
import random

OUTPUT_FILE = "products_for_woo_PLACEHOLDER.csv"

# Mock Data for Car Audio
mock_data = [
    ("Kicker 46CXA8001", "Mono Amplifier", "199.99", "Amplifiers", "https://example.com/kicker-amp.jpg"),
    ("JL Audio JD400/4", "4-Channel Amplifier", "279.99", "Amplifiers", "https://example.com/jl-amp.jpg"),
    ("Alpine S-W12D4", "12-Inch Subwoofer", "149.95", "Subwoofers", "https://example.com/alpine-sub.jpg"),
    ("Skar Audio EVL-12", "Competition Subwoofer", "219.99", "Subwoofers", "https://example.com/skar-sub.jpg"),
    ("Kenwood Excelon KFC-X174", "6.5 Inch Speakers", "89.99", "Speakers", "https://example.com/kenwood-spk.jpg"),
    ("Pioneer DMH-W4660NEX", "Digital Multimedia Receiver", "499.00", "Head Units", "https://example.com/pioneer-hu.jpg"),
    ("Rockford Fosgate P3D4-12", "Punch Series Sub", "179.99", "Subwoofers", "https://example.com/rockford-sub.jpg"),
    ("Sony XAV-AX5600", "CarPlay Receiver", "448.00", "Head Units", "https://example.com/sony-hu.jpg"),
    ("Focal PC 165", "Performance Coaxial Kit", "169.99", "Speakers", "https://example.com/focal-spk.jpg"),
    ("Sundown Audio SA-12 V.2", "12 Inch D4 Subwoofer", "289.99", "Subwoofers", "https://example.com/sundown-sub.jpg")
]

products = []

for title, desc, price, cat, img in mock_data:
    products.append({
        'Type': 'simple',
        'SKU': f"OG-{random.randint(10000,99999)}",
        'Name': title,
        'Published': 1,
        'Is featured?': 0,
        'Visibility in catalog': 'visible',
        'Short description': f"Premium {desc} available at Treasure Point.",
        'Description': f"<h3>{title}</h3><p>Experience the best audio quality with the {title}. Perfect for any setup.</p><ul><li>High Power Handling</li><li>Crystal Clear Sound</li><li>Authorized Dealer Warranty</li></ul>",
        'Tax status': 'taxable',
        'In stock?': 1,
        'Stock': 50,
        'Regular price': price,
        'Categories': f"Car Audio > {cat}",
        'Images': img,
        'Attribute 1 name': 'Brand',
        'Attribute 1 value': title.split(' ')[0]
    })

df = pd.DataFrame(products)
df.to_csv(OUTPUT_FILE, index=False)

print(f"\n✅ SUCCESS! Created {len(products)} placeholder products.")
print(f"📂 Saved to: {OUTPUT_FILE}")
print("👉 You can now import this file into WooCommerce to test your layout.")
