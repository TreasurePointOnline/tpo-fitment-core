import requests
import pandas as pd
import json
import random

# Shopify JSON Endpoint
TARGET_URL = "https://www.ogaudio.com/products.json?limit=250"
OUTPUT_FILE = "products_for_woo_json.csv"

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

def harvest_json():
    print(f"📡 Connecting to JSON endpoint: {TARGET_URL}...")
    products_out = []
    
    try:
        response = requests.get(TARGET_URL, headers=HEADERS)
        
        if response.status_code != 200:
            print(f"❌ Failed to fetch JSON: {response.status_code}")
            return

        data = response.json()
        
        if 'products' not in data:
            print("⚠️ JSON found, but no 'products' key. (Shopify API might be disabled)")
            return

        items = data['products']
        print(f"🔎 Found {len(items)} products in JSON feed. Processing...")

        for item in items:
            title = item.get('title', '')
            body_html = item.get('body_html', '')
            vendor = item.get('vendor', '')
            product_type = item.get('product_type', '')
            
            # Images
            images = item.get('images', [])
            image_url = images[0]['src'] if images else ""

            # Variants (Prices)
            variants = item.get('variants', [])
            price = "0.00"
            sku = ""
            if variants:
                price = variants[0].get('price', "0.00")
                sku = variants[0].get('sku', "")
            
            if not sku:
                sku = f"OG-{random.randint(10000,99999)}"

            # Add to list
            products_out.append({
                'Type': 'simple',
                'SKU': sku,
                'Name': title,
                'Published': 1,
                'Is featured?': 0,
                'Visibility in catalog': 'visible',
                'Short description': f"{product_type} by {vendor}",
                'Description': body_html, # Full HTML description
                'Tax status': 'taxable',
                'In stock?': 1,
                'Stock': 100,
                'Regular price': price,
                'Categories': f"Car Audio > {product_type}",
                'Images': image_url,
                'Attribute 1 name': 'Brand',
                'Attribute 1 value': vendor
            })

        # SAVE
        if products_out:
            df = pd.DataFrame(products_out)
            df.to_csv(OUTPUT_FILE, index=False)
            print(f"\n✅ SUCCESS! Harvested {len(products_out)} products via JSON.")
            print(f"📂 Saved to: {OUTPUT_FILE}")
        else:
            print("⚠️ No products extracted from JSON.")

    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    harvest_json()
