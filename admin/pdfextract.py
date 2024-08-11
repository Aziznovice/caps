import sys
import os
import PyPDF2
import requests
import json
# pip install PyPDF2
#pip install requests 
# Path to the PDF file
script_directory = os.path.dirname(os.path.abspath(__file__))
document_path = os.path.join(script_directory, sys.argv[1])

# Open the PDF file
with open(document_path, "rb") as file:
    pdf_reader = PyPDF2.PdfReader(file)

    # Initialize an empty string to store the extracted text
    document_content = ""

    # Iterate through the first 10 pages and extract text
    for page_number in range(min(10, len(pdf_reader.pages))): 
        page = pdf_reader.pages[page_number]
        document_content += page.extract_text() 
# Define the payload
payload = {
    "text": document_content,
    "entities": [
        {
            "var_name": "title",
            "type": "string",
            "description": "title",
        },
        {
            "var_name": "author",
            "type": "string",
            "description": "separate each Author by ';' ",
        },
        {
            "var_name": "introduction",
            "type": "string",
            "description": "generate a long informative summary of this text =",
            #  "description": "Retrieve full abstract;",
        },
        # Add other entities as needed
    ],
}

# textraction.ai API endpoint
url = "https://ai-textraction.p.rapidapi.com/textraction" 

# Headers with your RapidAPI key
headers = {
    "content-type": "application/json",
    "X-RapidAPI-Key": "6e6a1126bfmshae63e922309d9d6p1bafcfjsn72a3b9f04f12",
    "X-RapidAPI-Host": "ai-textraction.p.rapidapi.com",
}
# marc api
# api keysubtitute = "809edb76ccmshdf1ce4f184daa25p17a5e2jsndaf160e80834"
# api keysubtitute = "31b9a9aa4amshf7bd9a221c562a0p11bf18jsn848810bc0052"
# api keysubtitute = "c39e164d52mshbc78a4c798af327p1c193ejsn13f1d016f2db"
# api keysubtitute = "e099141953msh30a239ce2a1ec7bp1e99f8jsnfb841f63c616"
# api keysubtitute = "8a8b8097e1msh4c78c655eb3a153p1b4fadjsnc55306265c76"
# api keysubtitute = "6e6a1126bfmshae63e922309d9d6p1bafcfjsn72a3b9f04f12"
# api keysubtitute = "19f494ad78mshaf581527e1cb9dbp125c72jsn0abe8eb1c060"
# api keysubtitute = "d6b715ae21msh5f65327042ddd97p15600ejsn8b7665007fd6"

# api keysubtitute = "e4890abc4cmshdc11d5c0cb202b0p1592abjsnc880b4eede80"
# sampang api
# api keysubtitute = "f77fd06a1dmsh4f5c08b14ea334cp1a2d26jsnb8525e33c351"

# Make the POST request
response = requests.post(url, json=payload, headers=headers)

# Print the response
result = json.loads(response.text)

print(json.dumps(result))  # Ensure the output is valid JSON with double quotes
