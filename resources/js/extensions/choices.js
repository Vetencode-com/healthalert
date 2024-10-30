import 'choices.js/public/assets/styles/choices.min.css';
import Choices from 'choices.js';
import isObjectEmpty from '../helper/isObjectEmpty';

document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.choices');
    
    elements.forEach(element => {
        // Map dataset attributes to Choices.js options
        const options = Object.keys(element.dataset).reduce((acc, key) => {
            // Convert data attribute keys to Choices.js compatible options
            const optionKey = key.replace(/-([a-z])/g, g => g[1].toUpperCase()); // Convert kebab-case to camelCase
            let value = element.dataset[key];
            
            // Convert "true" / "false" strings to booleans
            if (value === 'true') value = true;
            else if (value === 'false') value = false;
            
            acc[optionKey] = value;
            return acc;
        }, {});
        
        // Add default options or specific configurations as needed
        if (element.classList.contains("multiple-remove")) {
            options.delimiter = options.delimiter || ",";
            options.editItems = options.editItems !== undefined ? options.editItems : true;
            options.maxItemCount = options.maxItemCount || -1;
            options.removeItemButton = options.removeItemButton !== undefined ? options.removeItemButton : true;
        }

        if (isObjectEmpty(options)) {
            new Choices(element);
        } else {
            new Choices(element, options);
        }
    });
});
