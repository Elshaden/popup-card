# Sample Popup Image Instructions

This document provides instructions for creating a sample welcome popup image for the Laravel Nova Popup Card package.

## Image Specifications

- **File name**: `acme-welcome-popup.png`
- **Dimensions**: 800px × 500px
- **Format**: PNG with transparent background

## Design Elements

The image should represent a popup modal with the following elements:

1. **Modal Container**:
   - White background
   - Rounded corners (10px radius)
   - Light shadow effect
   - Width: 50% of the screen (as per the '1/2' width setting)

2. **Modal Header**:
   - Title: "Welcome to ACME Company"
   - Font: Bold, sans-serif, 24px
   - Centered text
   - Dark gray color (#333333)

3. **Modal Body**:
   - Message: "Thank you for joining ACME Company! We're excited to have you on board. Our team is dedicated to providing exceptional service and innovative solutions to meet your needs."
   - Font: Regular, sans-serif, 16px
   - Line height: 1.5
   - Medium gray color (#555555)
   - Padding: 20px

4. **Modal Footer**:
   - Light gray background (#f7f7f7)
   - "Close" button on the left (blue button with white text)
   - "Do Not Show Again" checkbox with label on the right

5. **Message Styling**:
   - Welcome message in indigo color (#4F46E5)
   - Bold text for emphasis
   - Centered text alignment

6. **Footer**:
   - Centered text alignment
   - Dark purple color (#5B21B6)
   - Contact information for support

## Visual Style

- Clean, modern interface
- Consistent with Laravel Nova's design language
- Professional appearance suitable for enterprise applications

## Example Layout

```
┌─────────────────────────────────────────────────┐
│                                                 │
│           Welcome to ACME Company               │
│                                                 │
│ ───────────────────────────────────────────────┼───
│                                                 │
│      Thank you for joining ACME Company!        │
│        We're excited to have you on board.      │
│     Our team is dedicated to providing          │
│  exceptional service and innovative solutions   │
│              to meet your needs.                │
│                                                 │
│     * Complete your profile information         │
│     * Explore our product catalog               │
│     * Set up your notification preferences      │
│     * Set up 2-factor authentication            │
│                                                 │
│  Please contact support@acme.com for more info  │
│                                                 │
│ ───────────────────────────────────────────────┼───
│                                                 │
│  [Close]                     □ Do Not Show Again│
│                                                 │
└─────────────────────────────────────────────────┘
```

## Sample HTML Content for Popup Cards

Below is sample HTML content that you can copy and paste into the "Body" field when creating a new popup card in your Laravel Nova admin panel. This HTML recreates the welcome message from ACME Company as described above.

```html
<div style="position: relative; font-family: sans-serif; line-height: 1.5; font-size: 16px; text-align: center;">
  <!-- Welcome message - centered with indigo color and bold -->
  <div>
    <p style="color: #4F46E5; font-weight: bold;">Thank you for joining <strong>ACME Company</strong>! We're excited to have you on board.</p>
    
    <p style="color: #555555;">Our team is dedicated to providing exceptional service and innovative solutions to meet your needs.</p>
    
    <div style="margin-top: 20px; padding: 15px; background-color: #f8fafc; border-left: 4px solid #4F46E5; border-radius: 4px; text-align: left;">
      <strong>Getting Started:</strong>
      <ul style="margin-top: 10px; padding-left: 20px;">
        <li>Complete your profile information</li>
        <li>Explore our product catalog</li>
        <li>Set up your notification preferences</li>
        <li>Set up 2-factor authentication for extra security</li>
      </ul>
    </div>
    
    <!-- Footer with contact information -->
    <div style="margin-top: 25px; text-align: center; color: #5B21B6; font-size: 14px;">
      Please contact support@acme.com if you require any more information
    </div>
  </div>
</div>
```

### How to Use This HTML

1. Go to your Laravel Nova admin panel
2. Navigate to the Popup Cards resource
3. Click "Create Popup Card"
4. Fill in the following fields:
   - **Name**: `welcome-message` (or any unique identifier)
   - **Title**: `Welcome to ACME Company`
   - **Body**: Copy and paste the HTML code above
   - **Published**: Check this box
   - **Active**: Check this box
5. Click "Create Popup Card" to save

### Customization Options

You can customize this HTML by:

- Changing the indigo color (replace `#4F46E5` with your brand color)
- Adjusting the text alignment or font weight as needed
- Modifying the welcome message text
- Adding additional sections or formatting as needed

## Usage in Documentation

This image will be used in the README.md file to illustrate how the popup card appears to users. It will help potential users visualize the package's functionality and appearance.