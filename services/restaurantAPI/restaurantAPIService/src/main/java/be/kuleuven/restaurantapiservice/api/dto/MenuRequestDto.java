package be.kuleuven.restaurantapiservice.api.dto;

import be.kuleuven.menu.FoodItem;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class MenuRequestDto {
    private final List<FoodItem> menuItems;

    public MenuRequestDto() {
        this.menuItems = new ArrayList<>();
    }

    public List<FoodItem> getMenuItems() {
        return Collections.unmodifiableList(menuItems);
    }

    public void setMenuItems(List<FoodItem> menuItems) {
        this.menuItems.clear();
        if (menuItems != null) {
            this.menuItems.addAll(menuItems);
        }
    }

    public void addMenuItem(FoodItem menuItem) {
        if (menuItem != null) {
            menuItems.add(menuItem);
        }
    }
}
