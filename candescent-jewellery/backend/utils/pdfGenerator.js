const PDFDocument = require('pdfkit');

const generateInvoicePDF = (order, settings) => {
  return new Promise((resolve, reject) => {
    try {
      const doc = new PDFDocument({ margin: 50 });
      const chunks = [];

      doc.on('data', (chunk) => chunks.push(chunk));
      doc.on('end', () => resolve(Buffer.concat(chunks)));
      doc.on('error', reject);

      // Header
      doc.fontSize(24).font('Helvetica-Bold').text(settings.storeName || 'Candescént', { align: 'center' });
      doc.fontSize(10).font('Helvetica').text('Jewellery Store', { align: 'center' });
      doc.moveDown();

      // Invoice title
      doc.fontSize(20).font('Helvetica-Bold').text('INVOICE', { align: 'center' });
      doc.moveDown();

      // Order details
      doc.fontSize(12).font('Helvetica');
      doc.text(`Order Number: ${order.orderNumber}`, { continued: false });
      doc.text(`Date: ${new Date(order.createdAt).toLocaleDateString('en-GB')}`, { continued: false });
      doc.text(`Status: ${order.status}`, { continued: false });
      doc.moveDown();

      // Customer details
      doc.fontSize(14).font('Helvetica-Bold').text('Customer Details:');
      doc.fontSize(12).font('Helvetica');
      doc.text(`Name: ${order.customerName}`);
      doc.text(`Phone: ${order.customerPhone}`);
      doc.text(`Address: ${order.customerAddress}`);
      doc.text(`Delivery Type: ${order.deliveryType}`);
      doc.moveDown();

      // Items table header
      doc.fontSize(14).font('Helvetica-Bold').text('Order Items:');
      doc.moveDown(0.5);

      const tableTop = doc.y;
      const itemX = 50;
      const qtyX = 300;
      const priceX = 370;
      const totalX = 470;

      doc.fontSize(11).font('Helvetica-Bold');
      doc.text('Item', itemX, tableTop);
      doc.text('Qty', qtyX, tableTop);
      doc.text('Price', priceX, tableTop);
      doc.text('Total', totalX, tableTop);

      doc.moveTo(50, doc.y + 5).lineTo(550, doc.y + 5).stroke();
      doc.moveDown(0.5);

      // Items
      doc.font('Helvetica').fontSize(10);
      order.items.forEach((item) => {
        const y = doc.y;
        doc.text(item.productName, itemX, y, { width: 230 });
        doc.text(item.quantity.toString(), qtyX, y);
        doc.text(`৳${item.productPrice.toFixed(2)}`, priceX, y);
        doc.text(`৳${item.subtotal.toFixed(2)}`, totalX, y);
        doc.moveDown(0.8);
      });

      doc.moveDown(0.5);
      doc.moveTo(50, doc.y).lineTo(550, doc.y).stroke();
      doc.moveDown();

      // Totals
      const totalsX = 400;
      doc.fontSize(11).font('Helvetica');
      doc.text('Subtotal:', totalsX, doc.y);
      doc.text(`৳${order.subtotal.toFixed(2)}`, totalX, doc.y);
      doc.moveDown(0.5);

      doc.text('Delivery Charge:', totalsX, doc.y);
      doc.text(`৳${order.deliveryCharge.toFixed(2)}`, totalX, doc.y);
      doc.moveDown(0.5);

      if (order.discount > 0) {
        doc.text('Discount:', totalsX, doc.y);
        doc.text(`- ৳${order.discount.toFixed(2)}`, totalX, doc.y);
        doc.moveDown(0.5);
      }

      doc.fontSize(13).font('Helvetica-Bold');
      doc.text('Total Payable:', totalsX, doc.y);
      doc.text(`৳${order.totalAmount.toFixed(2)}`, totalX, doc.y);

      // Footer
      doc.fontSize(10).font('Helvetica').moveDown(3);
      doc.text('Thank you for your purchase!', { align: 'center' });

      doc.end();
    } catch (error) {
      reject(error);
    }
  });
};

module.exports = { generateInvoicePDF };
