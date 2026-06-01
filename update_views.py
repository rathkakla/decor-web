import os
import re
from pathlib import Path

directory = Path('resources/views/customer')

nav_pattern = re.compile(
    r'<header[^>]*>.*?</header>', 
    re.DOTALL | re.IGNORECASE
)

sidebar_pattern = re.compile(
    r'<aside class="w-72[^>]*>.*?</aside>', 
    re.DOTALL | re.IGNORECASE
)

for file_path in directory.rglob('*.blade.php'):
    if 'partials' in str(file_path): continue
    if 'chat.blade.php' == file_path.name: continue

    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    new_content = content

    if '<header ' in new_content or '<header>' in new_content:
        new_content = nav_pattern.sub(r"@include('customer.partials.navbar')", new_content)

    if '<aside class="w-72' in new_content:
        new_content = sidebar_pattern.sub(r"@include('customer.partials.sidebar')", new_content)

    if new_content != content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f'Updated {file_path.name}')