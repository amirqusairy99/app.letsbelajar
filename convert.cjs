const fs = require('fs');
const path = require('path');

const replacements = {
    // Layout
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
    
    // Spacing
    'gap-1': 'gap-1',
    'gap-2': 'gap-2',
    'gap-3': 'gap-4',
    'gap-4': 'gap-6',
    
    // Typography
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
    
    // Components
    'card-body': 'p-6',
    'card-header': 'flex flex-col space-y-1.5 p-6 border-b border-border',
    'card-footer': 'flex items-center p-6 border-t border-border',
    'list-group-item': 'relative flex w-full items-center justify-between border-b border-border py-3 px-4 last:border-0 hover:bg-muted/50 transition-colors',
    'list-group': 'flex flex-col rounded-md border border-border bg-card',
    'table-responsive': 'w-full overflow-auto',
    'table': 'w-full caption-bottom text-sm',
    
    // Forms
    'form-label': 'text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70',
    'form-text': 'text-[0.8rem] text-muted-foreground',
};

function replaceInFile(filepath) {
    let content = fs.readFileSync(filepath, 'utf8');
    const original = content;

    content = content.replace(/\bbtn\s+btn-primary\b/g, 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2');
    content = content.replace(/\bbtn\s+btn-danger\b/g, 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground shadow-sm hover:bg-destructive/90 h-9 px-4 py-2');
    content = content.replace(/\bbtn\s+btn-secondary\b/g, 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-secondary text-secondary-foreground shadow-sm hover:bg-secondary/80 h-9 px-4 py-2');
    content = content.replace(/\bbtn\s+btn-outline-secondary\b/g, 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2');
    content = content.replace(/\bbtn\b/g, 'inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2');
    
    content = content.replace(/\bform-control\b/g, 'flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50');
    content = content.replace(/\bform-select\b/g, 'flex h-9 w-full items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50');
    
    content = content.replace(/\bcard\b(?!-)/g, 'rounded-xl border border-border bg-card text-card-foreground shadow');
    content = content.replace(/\bshadow-sm\b/g, 'shadow-sm');
    content = content.replace(/\bborder-0\b/g, 'border-0');
    
    content = content.replace(/\bcontainer\b/g, 'container mx-auto px-4 md:px-8');
    
    for (const [oldClass, newClass] of Object.entries(replacements)) {
        const regex = new RegExp('\\b' + oldClass + '\\b', 'g');
        content = content.replace(regex, newClass);
    }
    
    content = content.replace(/ +/g, ' ');

    if (content !== original) {
        fs.writeFileSync(filepath, content, 'utf8');
        console.log(`Updated ${filepath}`);
    }
}

function walkDirectory(dir) {
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const filepath = path.join(dir, file);
        if (fs.statSync(filepath).isDirectory()) {
            walkDirectory(filepath);
        } else if (file.endsWith('.blade.php')) {
            const skipFiles = ['app.blade.php', 'guest.blade.php', 'navbar.blade.php', 'sidebar.blade.php', 'dashboard.blade.php'];
            if (!skipFiles.includes(file)) {
                replaceInFile(filepath);
            }
        }
    }
}

walkDirectory('resources/views');
console.log('Conversion complete.');
