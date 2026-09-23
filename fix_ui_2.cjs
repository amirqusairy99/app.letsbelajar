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

  // Fix <text-4xl ... class="...">...</text-4xl ...>
  // We use regex to grab the tag open and close
  content = content.replace(/<text-[a-zA-Z0-9- ]+([^>]*?)>([\s\S]*?)<\/text-[^>]*>/g, (match, attrs, inner) => {
    let classMatch = attrs.match(/class="([^"]*)"/);
    let classes = classMatch ? classMatch[1] : '';
    let tagName = 'div';
    if (classes.includes('text-2xl') || classes.includes('text-xl') || classes.includes('text-3xl')) {
      tagName = 'h2';
    }
    
    // clean up attrs
    let cleanAttrs = attrs;
    if (cleanAttrs) cleanAttrs = cleanAttrs.trim();
    if (cleanAttrs.length > 0) cleanAttrs = ' ' + cleanAttrs;
    
    return `<${tagName}${cleanAttrs}>${inner}</${tagName}>`;
  });
  
  // Also some tags are <text-sm ...> ... </text-sm>
  content = content.replace(/<text-[a-zA-Z0-9- ]+([^>]*?)>([\s\S]*?)<\/text-[^>]*>/g, (match, attrs, inner) => {
    let cleanAttrs = attrs ? ' ' + attrs.trim() : '';
    return `<div${cleanAttrs}>${inner}</div>`;
  });
  
  // Fix <w-full caption-bottom text-sm class="...">
  content = content.replace(/<w-full caption-bottom text-sm([^>]*)>/g, '<table$1>');
  content = content.replace(/<\/w-full caption-bottom text-sm>/g, '</table>');

  // Fix var(--js-text-primary) to text-foreground, var(--js-text-muted-foreground) to text-muted-foreground
  content = content.replace(/color:\s*var\(--js-text-primary\);?/g, '');
  content = content.replace(/color:\s*var\(--js-text-muted-foreground\);?/g, '');
  content = content.replace(/background:\s*linear-gradient\([^,]+,\s*var\(--js-[^)]+\)[^;]+;?/g, '');
  
  if (content !== original) {
    fs.writeFileSync(filePath, content);
    console.log('Fixed', filePath);
  }
});
console.log('Done');
