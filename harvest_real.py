import time
import pandas as pd
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.by import By

# START AT HOMEPAGE - It's usually the most accessible
TARGET_URL = "https://www.ogaudio.com"
OUTPUT_FILE = "products_real_data_final.csv"

def smash_and_grab():
    print("🤖 Launching Chrome Bot (Smash & Grab Mode)...")
    
    options = webdriver.ChromeOptions()
    options.add_argument('--start-maximized')
    options.add_argument('--disable-blink-features=AutomationControlled')
    
    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)
    
    try:
        print(f"📡 Navigating to {TARGET_URL}...")
        driver.get(TARGET_URL)
        time.sleep(5)
        
        # Scroll to trigger lazy loads
        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(3)
        
        print("   Hunting for product links...")
        # Get ALL links
        elements = driver.find_elements(By.TAG_NAME, "a")
        
        products = []
        seen_urls = set()
        
        for elem in elements:
            try:
                href = elem.get_attribute('href')
                if href and '/products/' in href:
                    if href in seen_urls: continue
                    seen_urls.add(href)
                    
                    # Try to extract data from the link card itself first (Fastest)
                    text = elem.text
                    
                    # Basic cleanup
                    title = text.split('\n')[0] if text else "Unknown Product"
                    if len(title) < 3: continue # Skip empty/short titles
                    
                    # Try to find an image inside this link
                    img_src = ""
                    try:
                        img = elem.find_element(By.TAG_NAME, "img")
                        img_src = img.get_attribute("src")
                        if img_src:
                            img_src = img_src.split('?')[0] # Remove size query params
                    except: pass
                    
                    # Price?
                    price = "0.00"
                    if "$" in text:
                        price = text.split('$')[1].split('\n')[0].strip()

                    products.append({
                        'Type': 'simple',
                        'SKU': f"OG-{len(products)+1}",
                        'Name': title,
                        'Published': 1,
                        'Regular price': price,
                        'Categories': 'Car Audio',
                        'Images': img_src,
                        'Description': f"Product link: {href}"
                    })
                    print(f"   + Found: {title[:25]}... (${price})")
            except: 
                continue

        # SAVE
        if products:
            df = pd.DataFrame(products)
            df.to_csv(OUTPUT_FILE, index=False)
            print(f"\n✅ SUCCESS! Harvested {len(products)} products.")
            print(f"📂 Saved to: {OUTPUT_FILE}")
        else:
            print("⚠️ Still found 0 products. The site structure is very unique.")

    except Exception as e:
        print(f"❌ Error: {e}")
    finally:
        driver.quit()

if __name__ == "__main__":
    smash_and_grab()