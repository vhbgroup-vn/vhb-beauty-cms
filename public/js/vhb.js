document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('[data-site-header]')
  const menuToggle = document.querySelector('[data-menu-toggle]')

  menuToggle?.addEventListener('click', () => {
    const isOpen = header?.classList.toggle('is-open') ?? false
    menuToggle.setAttribute('aria-expanded', String(isOpen))
    menuToggle.setAttribute('aria-label', isOpen ? 'Đóng menu' : 'Mở menu')
  })

  document.querySelectorAll('[data-site-menu] a').forEach((link) => {
    link.addEventListener('click', () => {
      header?.classList.remove('is-open')
      menuToggle?.setAttribute('aria-expanded', 'false')
      menuToggle?.setAttribute('aria-label', 'Mở menu')
    })
  })

  document.querySelectorAll('[data-carousel]').forEach((carousel) => {
    const slides = [...carousel.querySelectorAll('[data-slide]')]
    const dots = [...carousel.querySelectorAll('[data-dot]')]
    const currentSlide = carousel.querySelector('[data-current-slide]')
    if (slides.length < 2) return

    let current = 0
    const show = (index) => {
      current = (index + slides.length) % slides.length
      slides.forEach((slide, i) => slide.classList.toggle('is-active', i === current))
      dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === current)
        dot.setAttribute('aria-selected', String(i === current))
      })
      if (currentSlide) currentSlide.textContent = String(current + 1).padStart(2, '0')
    }

    dots.forEach((dot, index) => dot.addEventListener('click', () => show(index)))
    show(0)
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      window.setInterval(() => show(current + 1), 6500)
    }
  })

  document.querySelectorAll('[data-gallery-thumb]').forEach((thumb) => {
    thumb.addEventListener('click', () => {
      const mainImage = document.querySelector('[data-main-product-image]')
      const imageSource = thumb.dataset.imageSrc

      if (!mainImage || !imageSource) return

      mainImage.src = imageSource
      document.querySelectorAll('[data-gallery-thumb]').forEach((item) => {
        const isActive = item === thumb
        item.classList.toggle('is-active', isActive)
        item.setAttribute('aria-pressed', String(isActive))
      })
    })
  })

  document.querySelectorAll('[data-variation-picker]').forEach((picker) => {
    const dataElement = picker.querySelector('[data-variation-data]')
    const selectors = [...picker.querySelectorAll('[data-variation-select]')]
    const price = document.querySelector('[data-product-price]')
    const sku = document.querySelector('[data-product-sku]')
    const stock = document.querySelector('[data-product-stock]')
    const note = picker.querySelector('[data-variation-note]')
    const ctaLabel = document.querySelector('[data-product-cta-label]')
    const mainImage = document.querySelector('[data-main-product-image]')
    let variations = []

    try {
      variations = JSON.parse(dataElement?.textContent || '[]')
    } catch (error) {
      variations = []
    }

    const syncVariation = () => {
      const selected = Object.fromEntries(selectors.map((select) => [select.dataset.attribute, select.value]))
      const variation = variations.find((item) => Object.entries(item.attributes || {}).every(([name, value]) => selected[name] === value))

      if (!variation) {
        if (note) note.textContent = 'This combination is not available.'
        return
      }

      if (price) price.textContent = variation.priceLabel
      if (sku) sku.textContent = variation.sku
      if (stock) stock.textContent = variation.stockLabel
      if (note) note.textContent = `${variation.stockLabel} for this option.`
      if (ctaLabel) ctaLabel.textContent = variation.priceLabel === 'Contact us for pricing' ? 'Contact us for pricing' : 'Ask about this product'
      if (mainImage && variation.imageUrl) mainImage.src = variation.imageUrl
    }

    selectors.forEach((select) => select.addEventListener('change', syncVariation))
    syncVariation()
  })
})
