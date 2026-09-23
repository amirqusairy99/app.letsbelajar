import os
import re

replacements = {
    # Layout
    'd-flex': 'flex',
    'd-inline-flex': 'inline-flex',
    'align-items-center': 'items-center',
    'align-items-start': 'items-start',
    'align-items-end': 'items-end',
    'justify-content-between': 'justify-between',
    'justify-content-center': 'justify-center',
    'justify-content-start': 'justify-start',
    'justify-content-end': 'justify-end',
    'flex-column': 'flex-col',
    'flex-wrap': 'flex-wrap',
    'flex-grow-1': 'flex-1',
    'w-100': 'w-full',
    'h-100': 'h-full',
    'min-vh-100': 'min-h-screen',
    
    # Spacing (Bootstrap to Tailwind roughly)
    'gap-1': 'gap-1',
    'gap-2': 'gap-2',
    'gap-3': 'gap-4',
    'gap-4': 'gap-6',
    
    # Typography
    'fw-bold': 'font-bold',
    'fw-semibold': 'font-semibold',
    'fw-medium': 'font-medium',
    'fw-normal': 'font-normal',
    'fw-light': 'font-light',
    'fst-italic': 'italic',
    'text-center': 'text-center',
    'text-start': 'text-left',
    'text-end': 'text-right',
    'text-muted': 'text-muted-foreground',
    'text-primary': 'text-primary',
    'text-danger': 'text-destructive',
    'text-success': 'text-green-600 dark:text-green-400',
    'text-warning': 'text-amber-600 dark:text-amber-400',
    'text-info': 'text-blue-600 dark:text-blue-400',
    'text-decoration-none': 'no-underline',
    'text-uppercase': 'uppercase',
    'small': 'text-sm',
    'fs-6': 'text-base',
    'fs-5': 'text-xl',
    'fs-4': 'text-2xl',
    'fs-3': 'text-3xl',
    'fs-2': 'text-4xl',
    'fs-1': 'text-5xl',
    'h1': 'text-4xl font-extrabold tracking-tight lg:text-5xl',
    'h2': 'text-3xl font-semibold tracking-tight',
    'h3': 'text-2xl font-semibold tracking-tight',
    'h4': 'text-xl font-semibold tracking-tight',
    'h5': 'text-lg font-semibold tracking-tight',
    'h6': 'text-base font-semibold tracking-tight',
    
    # Components
    'card-body': 'p-6',
    'card-header': 'flex flex-col space-y-1.5 p-6 border-b border-border',
    'card-footer': 'flex items-center p-6 border-t border-border',
    'list-group-item': 'relative flex w-full items-center justify-between border-b border-border py-3 px-4 last:border-0 hover:bg-muted/50 transition-colors',
    'list-group': 'flex flex-col rounded-md border border-border bg-card',
    'table-responsive': 'w-full overflow-auto',
    'table': 'w-full caption-bottom text-sm',
    
    # Shadcn Base Classes for complex components
    'form-label': 'text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70',
    'form-text': 'text-[0.8rem] text-muted-foreground',
}

def replace_in_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original = content
    
    # Complex replacements via regex to handle multiple classes
    content = re.sub(r'\bbtn\s+btn-primary\b', 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2', content)
    content = re.sub(r'\bbtn\s+btn-danger\b', 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground shadow-sm hover:bg-destructive/90 h-9 px-4 py-2', content)
    content = re.sub(r'\bbtn\s+btn-secondary\b', 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-secondary text-secondary-foreground shadow-sm hover:bg-secondary/80 h-9 px-4 py-2', content)
    content = re.sub(r'\bbtn\s+btn-outline-secondary\b', 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2', content)
    content = re.sub(r'\bbtn\b', 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2', content)
    
    content = re.sub(r'\bform-control\b', 'flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50', content)
    content = re.sub(r'\bform-select\b', 'flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50', content)
    
    content = re.sub(r'\bcard\b(?!-)', 'rounded-xl border border-border bg-card text-card-foreground shadow', content)
    content = re.sub(r'\bshadow-sm\b', 'shadow-sm', content)
    content = re.sub(r'\bborder-0\b', 'border-0', content)
    
    content = re.sub(r'\bcontainer\b', 'container mx-auto px-4 md:px-8', content)
    
    for old, new in replacements.items():
        content = re.sub(r'\b' + old + r'\b', new, content)

    # Cleanup multiple spaces
    content = re.sub(r' +', ' ', content)
    
    if content != original:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)

def walk_directory(directory):
    for root, _, files in os.walk(directory):
        for file in files:
            if file.endswith('.blade.php'):
                # skip already overhauled ones
                if file in ['app.blade.php', 'guest.blade.php', 'navbar.blade.php', 'sidebar.blade.php', 'dashboard.blade.php']:
                    continue
                replace_in_file(os.path.join(root, file))

if __name__ == "__main__":
    walk_directory('resources/views')
    print("Conversion complete.")
