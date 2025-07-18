// src/islands/Yii2Button.jsx
export default function Yii2Button({ label, url, style = 'primary' }) {
  return (
    <a 
      href={url} 
      className={`btn btn-${style}`}
      onClick={(e) => {
        e.preventDefault();
        // Optional: Add Yii2 AJAX handling here
        window.location.href = url;
      }}
    >
      {label}
    </a>
  );
}