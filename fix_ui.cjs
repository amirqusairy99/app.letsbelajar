const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
  fs.readdirSync(dir).forEach(f => {
    let dirPath = path.join(dir, f);
    let isDirectory = fs.statSync(dirPath).isDirectory();
    isDirectory ? walkDir(dirPath, callback) : callback(path.join(dir, f));
  });
}

const viewsDir = path.join(__dirname, 'resources', 'views');

walkDir(viewsDir, function(filePath) {
  if (!filePath.endsWith('.blade.php')) return;

  let content = fs.readFileSync(filePath, 'utf8');
  let original = content;

  // 1. Fix <text-*> tags
  // We'll replace with <div class="..."> or <h2 class="..."> depending on the original content, but let's just use <div class="..."> for safety to avoid nested p in p.
  // Actually, some have `style="..."`. Let's capture attributes.
  content = content.replace(/<text-[a-zA-Z0-9- ]+\s+class="([^"]+)"(?:([^>]*)>|>)([\s\S]*?)<\/text-[^>]+>/g, (match, classes, otherAttrs, inner) => {
    otherAttrs = otherAttrs ? ' ' + otherAttrs.trim() : '';
    // If it looks like a large heading
    if (classes.includes('text-2xl') || classes.includes('text-xl') || classes.includes('text-3xl')) {
      return `<h2 class="${classes}"${otherAttrs}>${inner}</h2>`;
    }
    return `<div class="${classes}"${otherAttrs}>${inner}</div>`;
  });

  // Catch text tags without class but with style
  content = content.replace(/<text-[a-zA-Z0-9- ]+\s+style="([^"]+)"(?:([^>]*)>|>)([\s\S]*?)<\/text-[^>]+>/g, (match, styles, otherAttrs, inner) => {
    otherAttrs = otherAttrs ? ' ' + otherAttrs.trim() : '';
    return `<div style="${styles}"${otherAttrs}>${inner}</div>`;
  });

  // 2. Fix text colors
  content = content.replace(/\btext-light\b/g, 'text-foreground');
  content = content.replace(/\btext-secondary\b/g, 'text-muted-foreground');
  content = content.replace(/\btext-dark\b/g, 'text-foreground');
  
  // 3. Fix standard bootstrap grids
  // row g-*
  content = content.replace(/class="([^"]*)\brow g-2\b([^"]*)"/g, 'class="$1grid grid-cols-1 md:grid-cols-12 gap-2$2"');
  content = content.replace(/class="([^"]*)\brow g-3\b([^"]*)"/g, 'class="$1grid grid-cols-1 md:grid-cols-12 gap-6$2"');
  content = content.replace(/class="([^"]*)\brow g-4\b([^"]*)"/g, 'class="$1grid grid-cols-1 md:grid-cols-12 gap-8$2"');
  content = content.replace(/class="([^"]*)\brow\b([^"]*)"/g, (match, p1, p2) => {
     if (p1.includes('grid ') || p2.includes(' gap-')) return match; // already grid
     return `class="${p1}grid grid-cols-1 md:grid-cols-12 gap-4${p2}"`;
  });

  // cols
  content = content.replace(/\bcol-12 col-md-1\b/g, 'md:col-span-1');
  content = content.replace(/\bcol-12 col-md-2\b/g, 'md:col-span-2');
  content = content.replace(/\bcol-12 col-md-3\b/g, 'md:col-span-3');
  content = content.replace(/\bcol-12 col-md-4\b/g, 'md:col-span-4');
  content = content.replace(/\bcol-12 col-md-5\b/g, 'md:col-span-5');
  content = content.replace(/\bcol-12 col-md-6\b/g, 'md:col-span-6');
  content = content.replace(/\bcol-12 col-md-7\b/g, 'md:col-span-7');
  content = content.replace(/\bcol-12 col-md-8\b/g, 'md:col-span-8');
  content = content.replace(/\bcol-12 col-md-9\b/g, 'md:col-span-9');
  content = content.replace(/\bcol-12 col-md-10\b/g, 'md:col-span-10');
  content = content.replace(/\bcol-12 col-md-11\b/g, 'md:col-span-11');
  content = content.replace(/\bcol-12 col-md-12\b/g, 'md:col-span-12');

  content = content.replace(/\bcol-12 col-lg-3\b/g, 'md:col-span-6 lg:col-span-3');
  content = content.replace(/\bcol-12 col-lg-4\b/g, 'md:col-span-6 lg:col-span-4');
  content = content.replace(/\bcol-12 col-lg-6\b/g, 'md:col-span-12 lg:col-span-6');
  content = content.replace(/\bcol-12 col-lg-8\b/g, 'md:col-span-12 lg:col-span-8');
  content = content.replace(/\bcol-12\b/g, 'md:col-span-12');

  // d-grid gap-2
  content = content.replace(/\bd-grid gap-2\b/g, 'flex flex-col gap-2');
  content = content.replace(/\bd-flex\b/g, 'flex');
  content = content.replace(/\bjustify-content-between\b/g, 'justify-between');
  content = content.replace(/\balign-items-center\b/g, 'items-center');

  // borders
  content = content.replace(/\bborder-bottom border-secondary\b/g, 'border-b border-border');
  content = content.replace(/\bborder-top border-secondary\b/g, 'border-t border-border');

  if (content !== original) {
    fs.writeFileSync(filePath, content);
    console.log('Fixed', filePath);
  }
});
console.log('Done');
