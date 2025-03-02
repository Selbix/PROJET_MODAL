import sys
import os
import pypdfium2
from PIL import Image

def pdf_to_jpg(pdf_path, output_path):
    if not os.path.exists(pdf_path):
        print(f"Error: {pdf_path} not found.")
        return
    
    try:
        # Load the PDF file
        pdf = pypdfium2.PdfDocument(pdf_path)

        # Access the first page (or iterate if needed)
        page = pdf[0]  # Index 0 refers to the first page

        # Render the page to a bitmap
        bitmap = page.render(scale=2.0)  # Adjust scale for quality

        # Convert the bitmap to a PIL Image object
        img = bitmap.to_pil()

        # Save the image to the specified path as JPEG
        img.save(output_path, format="JPEG")
        print(f"Thumbnail saved at: {output_path}")

    except Exception as e:
        print(f"Error: {str(e)}")

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python convert.py <pdf_path> <output_path>")
    else:
        pdf_to_jpg(sys.argv[1], sys.argv[2])
