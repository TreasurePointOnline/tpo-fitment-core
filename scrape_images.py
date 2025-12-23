import os
import requests
from bs4 import BeautifulSoup
from urllib.parse import urljoin
import time

# CONFIGURATION: The Exact "Hit List"
# ---------------------------------------------------------
TARGETS = {
    "Amplifiers": "https://og-audio.com/11-amplifier",
    "Subwoofers": "https://og-audio.com/12-subwoofers",
    "Audio_Speakers": "https://og-audio.com/9-art",
    "Empty_Enclosures": "https://og-audio.com/13-empty-enclosures"
}

BASE_FOLDER = "product_staging"
# ---------------------------------------------------------

def download_category(category_name, url):
    # 1. Create a folder for this category
    save_folder = os.path.join(BASE_FOLDER, category_name)
    if not os.path.exists(save_folder):
        os.makedirs(save_folder)
    
    print(f"\n🕷️  Crawling Category: {category_name}...")
    print(f"    URL: {url}")

    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    }

    try:
        response = requests.get(url, headers=headers)
        if response.status_code != 200:
            print(f"    ❌ Failed to load page (Status: {response.status_code})")
            return

        soup = BeautifulSoup(response.text, 'html.parser')
        
        # Find all images on the page
        images = soup.find_all('img')
        
        count = 0
        seen_urls = set()

        for img in images:
            img_url = img.get('src')
            
            if not img_url: continue
            
            # 1. Fix Relative URLs
            img_url = urljoin(url, img_url)

            # 2. Filter out junk (Logos, Icons, Spacers)
            # FIX: Added 'x' before 'in'
            if any(x in img_url.lower() for x in ['logo', 'icon', 'button', 'cart', 'search', 'blank']):
                continue

            # 3. Duplicate Check
            if img_url in seen_urls:
                continue
            seen_urls.add(img_url)
            
            # 4. Generate Filename
            ext = img_url.split('.')[-1].split('?')[0]
            if len(ext) > 4: ext = "jpg"
            
            filename = os.path.join(save_folder, f"{category_name}_{count+1}.{ext}")
            
            try:
                # Download
                img_data = requests.get(img_url, headers=headers).content
                
                # Filter by file size (Skip tiny images < 5KB)
                if len(img_data) < 5120: 
                    continue

                with open(filename, 'wb') as handler:
                    handler.write(img_data)
                    
                print(f"    ⬇️  Got: {category_name}_{count+1}.{ext}")
                count += 1
                time.sleep(0.2)
                
            except Exception as e:
                pass 
            
            if count >= 50: 
                break

        print(f"✅ Finished {category_name}: {count} images saved.")

    except Exception as e:
        print(f"❌ Error scraping {category_name}: {e}")

if __name__ == "__main__":
    print("🚀 Starting OG Audio Scraper (Exact Links)...")
    for cat, link in TARGETS.items():
        download_category(cat, link)
    print("\n🏁 All Done! Check the 'product_staging' folder.")