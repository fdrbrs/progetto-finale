<template>
  <div class="my_container py-4">

    <!-- RIRSTORANTE -->
    <div class="restaurant">
      <div class="img-restaurant-container">
        <img :src=" restaurant.image == null ? '../img/cover_restaurant.jpg' : '../storage/' + restaurant.image" alt="" />
        <h1 class="restaurant_title">{{ restaurant.name }}</h1>
      </div>

      <!-- informazioni del ristorante -->
      <div class="restaurant_details">
          <p class="">{{ restaurant.description }}</p>
          <span class="categories" v-for="category in restaurant.categories" :key="category.id" >#{{ category.name }}</span>
          <div class="address"><i class="fas fa-map-marker-alt"></i> {{ restaurant.address }}, {{ restaurant.city }}, {{ restaurant.cap }}</div>
      </div>

      <!-- MENU -->
      <div class="menu">
        <h3 class="menu_title">Menu</h3>

        <!-- PIATTO SINGOLO -->
        <div class="plates">
          <div  @click="selectModalPlate(plate)" class="my_card" v-for="plate in visible_plates" :key="plate.id">

              <img @click="showModal = true" class="plate-img" :src="'../storage/' + plate.image" :alt="plate.name" />

              <div class="card_body">
                <div @click="showModal = true" class="details">
                  <h5 class="card_title">{{ plate.name }}</h5>
                  <div class="card_text">{{ plate.description }}</div>
                  <div class="price_btn"><strong>{{ plate.price }} €</strong></div>
                </div>
                <div @click="storagePlate(plate)" class="add_plate w-100 btn btn-success"><strong>Aggiunti al carrello</strong></div>
              </div>

          </div>
        </div>

      </div>
    </div>

    <!-- MODALE DEL PIATTO -->
    <div v-if="showModal" class="overlay_modal">

      <div class="plate_modal">
        <div class="top">
            <img :src=" '../storage/' + modal_plate.image " alt="" >
            <i @click="showModal = false" class="fas fa-times"></i>
        </div>

        <div class="bottom">
          <div class="details">
            <h2>{{modal_plate.name}}</h2>
            <h3>{{modal_plate.price}} €</h3>
            <p class="description">{{modal_plate.description}}</p>
          </div>

          <!-- aggiungi al carrello NON FUNZIONA (NON SEI IN UN CICLO) -->
          <div @click="storagePlate(modal_plate)" class="add_plate w-100 btn btn-success"><strong>Aggiunti al carrello</strong></div>
        </div>
      </div>

    </div>

    <!-- CARRELLO -->
    <div class="cart">
      <div class="cart_heading">
        <i class="fas fa-shopping-cart"></i>
        <span class="total_price">totale {{ cart_price() }} €</span>
      </div>

      <div class="content">
        <div class="text-center mt-5" v-if="plates.length == 0">Il carrello è vuoto! <br> Seleziona un piatto per visualizzarlo QUI 👇</div>
        <div class="cart_item" v-for="(plate, index) in plates" :key="plate.id">
          <span>{{ plate.name }}</span>

          <div class="actions">
            <i @click="minusPlate(plate, index)" class="fas fa-minus-circle text-danger" ></i>
            <span class="item_price">{{ plate.qty }}</span>
            <i @click="storagePlate(plate, index)" class="fas fa-plus-circle text-success" ></i>
          </div>
        </div>
        <span v-if="plates.length != 0" @click="emptyCart()" class="empty_cart bg-danger text-white">Svuota il carrello <i class="fas fa-trash-alt"></i></span>
        
        <a :class="plates.length > 0 ? 'btn-success' : 'd-none'"  class="checkout_link btn" href="../cart ">Vai alla cassa</a>
      </div>
    </div>

    <!-- CARRELLO MOBILE -->
    <div class="mobile_cart">
      <i v-if="mobileCart == false" @click="showMobileCart()" class="fas fa-shopping-bag"></i>

      <div v-else class="cart_modal">
        <div class="cart_heading">
          <span class="total_price">totale {{ cart_price() }} €</span>
          <span @click="showMobileCart()"><i class="fas fa-times"></i></span>
        </div>

        <div class="content">
            <div class="text-center mt-4" v-if="plates.length == 0">Il carrello è vuoto! <br> Seleziona un piatto per visualizzarlo QUI 👇</div>
            <div class="cart_item" v-for="(plate, index) in plates" :key="plate.id">
                <span span>{{ plate.name }}</span>

                <div class="actions">
                  <i @click="minusPlate(plate, index)" class="fas fa-minus-circle text-danger" ></i>
                  <span class="item_price">{{ plate.qty }}</span>
                  <i @click="storagePlate(plate, index)" class="fas fa-plus-circle text-success" ></i>
                </div>
            </div>
          <!-- <span v-if="plates.length != 0" @click="emptyCart()" class="empty_cart bg-danger text-white">Svuota il carrello <i class="fas fa-trash-alt"></i></span> -->

          <a class="checkout_link btn btn-success" href="../cart">Vai alla cassa</a>
        </div>
      </div>
    </div>
  
  </div>
</template>

<script>
import Axios from "axios";

export default {
  data() {
    return {
      restaurant: {},
      restaurant_plates: {},
      visible_plates: [],
      mobileCart: false, //dati carrello
      restaurantOrder: [], //dati carrello
      plates: [], //dati carrello
      modal_plate: {}, //modale del piatto singolo
      showModal : false, //mostra modale
    };
  },

  methods: {
    selectModalPlate(plate){
      this.modal_plate = plate;
    },
    showMobileCart(){
      if (this.mobileCart) {
        this.mobileCart = false;
        console.log('chiudi il carrello');
        console.log(this.mobileCart);
      } else {
        this.mobileCart = true;
        console.log(this.mobileCart);
        console.log('apri il carrello');
      }
    },
    cart_price() {
      let totalPrice = 0;
      if (this.plates != null) {
        this.plates.forEach((plate) => {
          totalPrice += plate.price * plate.qty;
        });
        return totalPrice.toFixed(2);
      }
    },
    callRestaurants() {
      Axios.get("/api/restaurants?id=" + this.$route.params.id)
        .then((resp) => {
          this.restaurant = resp.data.data;
          this.restaurant_plates = resp.data.data.plates;
          // funzione per prendere i piatti visibili
          this.restaurant_plates.forEach(food => {
            if (food.visible == true) {
              this.visible_plates.push(food);
            }
          })
        })
        .catch((e) => {
          console.error(e);
        });
    },
    storagePlate(plate) {
      let foodInCart = [];
      this.plates.forEach((food) => {
        foodInCart.push(food.id);
      });
      if (foodInCart.includes(plate.id)) {
        this.plates.forEach((food) => {
          if (food.id == plate.id) {
            food.qty++;
          }
        });
      } else {
        foodInCart.push(plate.id);
        if (this.restaurantOrder != plate.restaurant_id) {
          if (this.plates.length == 0) {
            this.plates.unshift(plate);
            this.restaurantOrder = plate.restaurant_id;
          } else if (
            confirm(
              "Attenzione, cambiando ristorante perderai gli ordini attuali!"
            )
          ) {
            this.emptyCart();
            this.plates.unshift(plate);
            this.restaurantOrder = plate.restaurant_id;
          }
        } else {
          this.plates.unshift(plate);
        }
      }
      this.savePlates();
      this.saveRestaurantOrder();
    },
    saveRestaurantOrder() {
      const parsed = JSON.stringify(this.restaurantOrder);
      localStorage.setItem("restaurant", parsed);
    },
    savePlates() {
      const parsed = JSON.stringify(this.plates);
      localStorage.setItem("plates", parsed);
    },
    minusPlate(plate) {
      let foodInCart = [];
      this.plates.forEach((food) => {
        foodInCart.push(food.id);
      });
      if (foodInCart.includes(plate.id)) {
        this.plates.forEach((food) => {
          if (food.id == plate.id) {
            if (food.qty == 1) {
              this.removePlate();
            } else {
              food.qty--;
            }
          }
        });
      }
      this.savePlates();
    },
    removePlate(i) {
      this.plates.splice(i, 1);
      this.savePlates();
    },
    emptyCart() {
      this.restaurantOrder = []; //cancella il ristorante assegnato al carrello
      //ciclo per resettare la quantita', risolve un errore quando non viene ricaricata la pagina
      this.plates.forEach(plate => {
        plate.qty = 1;
      })
      this.plates = []; // cancella tutti i piatti
      this.savePlates();
      this.saveRestaurantOrder();
    },
    getPlates() {
      if (localStorage.getItem("plates") == null) {
        this.savePlates();
      }
      this.plates = JSON.parse(localStorage.getItem("plates"));
    },
    getRestaurantOrder() {
      if (localStorage.getItem("restaurant") == null) {
        this.saveRestaurantOrder();
      }
      this.restaurantOrder = JSON.parse(localStorage.getItem("restaurant"));
    },
  },

  mounted() {
    this.callRestaurants();
    this.getPlates();
    this.getRestaurantOrder();
    
  },
}
</script>

<style lang="scss" scoped>
.my_container {
  display: flex;

  @media screen and (max-width:1199.98px) {
    display: block;
  }
}

.restaurant {
  width: 80%;
  @media screen and (max-width:1199.98px) {
    width: 100%;
  }

  .restaurant_details{
    margin-top: 1rem;
    padding: 4rem;
    font-size: 1.1rem;
    border: 1px solid black;

  }

  .categories {
    padding: 0 1rem;
  }

  .address {
    font-size: 0.9rem;
    margin-top: 1rem;
  }

  .img-restaurant-container {
    height: 300px;
    width: 100%;
    position: relative;
    overflow: hidden;
    border-top-left-radius: 300px;
    border-bottom-right-radius: 300px;
    @media screen and (max-width: 767.98px) {
      border-radius: 2rem;
    }
  
    .restaurant_title{
      position: absolute;
      bottom: .5rem;
      padding-left: 7rem;
      background-color: white;
      width: 100%;
    }

    img {
      width: 100%;
      height: inherit;
      object-fit: cover;
    }
  }

  .menu {
    margin-top: 1rem;

    .menu_title {
      background-color: #F8B735;
      padding: 1rem;
      color: #2B898B;
      font-weight: bold;
      border-radius: .5rem;
    }
  }

  .plates {
    display: flex;
    flex-wrap: wrap;

    .my_card {
        border-radius: .5rem;
        overflow: hidden;
        width: calc(100% / 4 - 2rem);
        max-height: 25rem;
        margin: 1rem;
        border: 1px solid rgb(238, 238, 238);
        display: flex;
        flex-direction: column;
        transition: .3s ease-in-out;
        
        @media screen and (max-width: 1399.98px) {
          width: calc(100% / 3 - 2rem);
        }
        @media screen and (max-width: 991.98px) {
          width: calc(100% / 3 - 2rem);
        }
        @media screen and (max-width: 767.98px) {
          width: calc(100% / 2 - 2rem);
        }
        @media screen and (max-width: 575.98px) {
          width: calc(100% - 2rem);
        }

        .plate-img {
          height: 40%;
          width: 100%;
          object-fit: cover;
        }
    
        .card_body {
          height: 60%;
          padding: 1rem;
          display: flex;
          flex-direction: column;
          justify-content: space-between;

          .card_text {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
          }

          .price_btn {
            text-align: end;
          }
        }

        .add_plate {
          transform: translateY(4rem);
          transition: all 0.2s ease-in-out;
        }

    &:hover {
      cursor: pointer;
      transform: translatey(-5px);
      box-shadow: 1px 5px 10px rgba(0, 0, 0, 0.336);
      color: inherit;

      .add_plate {
        transform: translateY(0);
      }
    }

    }  
  }
}

.overlay_modal {
  // overlay
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.466);
  z-index: 999;
  overflow: hidden;
  // centrare la modale
  display: flex;
  justify-content: center;
  align-items: center;

  .plate_modal{
    @media screen and (max-width:1399.98px) {
        width: 40%;
    }
    @media screen and (max-width:991.98px) {
        width: 50%;
    }
    @media screen and (max-width:767.98px) {
        width: 60%;
    }
    @media screen and (max-width:575.98px) {
        width: 80%;
    }
    background-color: white;
    width: 40%;
    max-height: 95vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-radius: .5rem;

    animation: show .3s ease-in-out;
    @keyframes show {
      from{
        transform: scale(.1);
        opacity: 0;
      }
      to{
        transform: scale(1);
        opacity: 1;
      }
    }

    .bottom{
      height: 90%;
      display: flex;
      overflow: auto;
      flex-direction: column;
      justify-content: space-between;

      .details {
        padding: 2rem;

        .description {
          text-align: justify;
        }
      }
    }
    .top{
      height: 200px;
      background-color: blanchedalmond;
      position: relative;

      img {
        border-top-left-radius: .5rem;
        border-top-right-radius: .5rem;
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .fa-times {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: white;
        border-radius: 50%;
        width: 3rem;
        height: 3rem;
        display: flex;
        justify-content: center;
        align-items: center;
      }
    }
  }
}

//carrello
.cart {
  height: fit-content;
  width: 20%;
  margin: 0 1rem;
  background-color: white;
  position: sticky;
  top: 0;
  box-shadow: 10px 5px 10px rgba(0, 0, 0, 0.089);
  // border-bottom-left-radius: .5rem;
  // border-bottom-right-radius: .5rem;
  border-radius: .5rem;

  @media screen and (max-width:1199.98px) {
    display: none;
  }
}
.mobile_cart {
    @media screen and (min-width:1199.98px) {
        display: none;
    }
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    bottom: 2rem;
    right: 2rem;

  .fa-shopping-bag {
    font-size: 2rem;
    padding: 2rem;
    border-radius: 50%;
    color: #2B898B;
    background-color: #F8B735;
    animation: show .3s ease-in-out;

    @keyframes show {
      from{
        transform: scale(.1);
        opacity: 0;
      }
      to{
        transform: scale(1);
        opacity: 1;
      }
    }
  }
  
  .cart_modal {
    width: 100%;
    overflow: hidden;
    animation: slide_up .3s ease-in-out;
    @keyframes slide_up {
      from{
          border-radius: 50%;
          width: 5rem;
          height: 5rem;
      }
      to{
          height: 13.3rem;
      }
    }
    box-shadow: 1px 5px 10px rgba(0, 0, 0, 0.418);
    width: 20rem;
    border-radius: .8rem;

    .cart_heading {
      animation: opacity .5s .1s ease-in-out;
      animation-fill-mode: backwards;
      @keyframes opacity {
        from{
            opacity: 0;
        }
        to{
            opacity: 1;
        }
      }
    }

    .content {
      animation: opacity .5s .1s ease-in-out;
      animation-fill-mode: backwards;
      min-height: 10rem;
    }
  }
}
.cart_heading {
  border-top-left-radius: .5rem;
  border-top-right-radius: .5rem;
  display: flex;
  justify-content: space-between;
  padding: 1rem;
  background-color: #2B898B;
  color: white;

  .total_price{
    font-weight: bold;
    font-size: 1.2rem;
  }
}
.content {
  min-height: 30rem;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  background-color: white;
  border-bottom-left-radius: .5rem;
  border-bottom-right-radius: .5rem;
  .cart_item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.3rem;
    border-bottom: 1px solid rgb(245, 245, 245);
  }
  .empty_cart {
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    padding: 0.3rem;
  }
  .checkout_link {
    margin-top: auto;
  }
}
.actions {
  display: flex;
  align-items: center;
  transition: .2s;
  .item_price {
  width: 1.3rem;
  text-align: center;
  font-weight: bold;
  }
  .fas:active {
  transform: scale(.9);
  }
}

//comune per tutte le icone
.fas {
  cursor: pointer;
  font-size: 1.4rem;
}
</style>