import requests
from bs4 import BeautifulSoup
import pandas as pd
import random
import time

# --- CONFIG ---
TARGET_URL = "https://www.ogaudio.com/collections/amplifiers" 
OUTPUT_FILE = "products_for_woo.csv"

# Enhanced Headers to bypass Mod_Security
HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'Accept-Language': 'en-US,en;q=0.9',
    'Referer': 'https://www.google.com/',
    'Upgrade-Insecure-Requests': '1',
    'Sec-Fetch-Dest': 'document',
    'Sec-Fetch-Mode': 'navigate',
    'Sec-Fetch-Site': 'cross-site',
    'Sec-Fetch-User': '?1',
    'Cache-Control': 'max-age=0',
}

def harvest():
    print(f"📡 Connecting to {TARGET_URL}...")
    products = []
    
    try:
        response = requests.get(TARGET_URL, headers=HEADERS)
        if response.status_code != 200:
            print(f"❌ Failed to load site: {response.status_code}")
            print(response.text[:500]) # Print beginning of error
            return

        soup = BeautifulSoup(response.text, 'html.parser')
        
        # Look for Shopify specific classes
        # Common Shopify selectors: .product-item, .grid__item, .product-card
        product_cards = soup.select('.product-item, .grid__item, .product-card, .card-wrapper') 
        
        if not product_cards:
             # Fallback: look for any div with an 'a' tag that has an 'img' inside (generic product card structure)
             print("   (Using generic fallback search...)")
             product_cards = [div for div in soup.find_all('div') if div.find('a') and div.find('img') and len(div.get_text(strip=True)) > 10][:20]

        print(f"🔎 Found {len(product_cards)} potential items. Processing...")

        count = 0
        for card in product_cards:
            try:
                # 1. Get Title
                # Try common headers
                title_tag = card.find(['h1', 'h2', 'h3', 'h4', 'span'], class_=lambda x: x and ('title' in x.lower() or 'name' in x.lower()))
                if not title_tag:
                     # Fallback: Find the link text
                     link = card.find('a')
                     if link: title = link.get_text(strip=True)
                     else: continue
                else:
                    title = title_tag.get_text(strip=True)
                
                # 2. Get Price
                price_tag = card.find(class_=lambda x: x and 'price' in x.lower())
                if price_tag:
                    # Remove currency symbols and noise
                    price = ''.join([c for c in price_tag.get_text() if c.isdigit() or c == '.'])
                else:
                    price = "0.00"
                
                # 3. Get Image
                img_tag = card.find('img')
                image_url = ""
                if img_tag:
                    # Check for data-src or src, often lazy loaded
                    image_url = img_tag.get('src') or img_tag.get('data-src') or img_tag.get('data-srcset')
                    if image_url:
                        if image_url.startswith('//'):
                            image_url = "https:" + image_url
                        elif image_url.startswith('/'):
                            image_url = "https://www.ogaudio.com" + image_url
                
                # 4. Cleanup
                description = f"High quality {title} from OG Audio."

                if not title or len(title) < 3: continue
                
                print(f"   -> Found: {title[:30]}... (${price})")

                products.append({
                    'Type': 'simple',
                    'SKU': f"OG-{random.randint(1000,9999)}",
                    'Name': title,
                    'Published': 1,
                    'Is featured?': 0,
                    'Visibility in catalog': 'visible',
                    'Short description': description,
                    'Description': description,
                    'Tax status': 'taxable',
                    'In stock?': 1,
                    'Stock': 100,
                    'Regular price': price,
                    'Categories': 'Car Audio > Amplifiers',
                    'Images': image_url
                })
                count += 1
                if count >= 20: break 

            except Exception as e:
                # print(f"Error parsing card: {e}")
                continue

        # SAVE TO CSV
        if products:
            df = pd.DataFrame(products)
            df.to_csv(OUTPUT_FILE, index=False)
            print(f"\n✅ SUCCESS! Harvested {len(products)} products.")
            print(f"📂 Saved to: {OUTPUT_FILE}")
        else:
            print("\n⚠️ No products extracted.")

    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    harvest()