/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GameFrame;

import static GameFrame.Ball.radio;

/**
 *
 * @author geiver.botello
 */
public class Raqueta extends Movil {
    public static final int anchoR = 20;
    public static final int altoR = 70;
    
    public Raqueta(int x, int y) {
        super();
        this.x = x;
        this.y = y;
        this.rect.setBounds(x, y, anchoR, altoR);
    }

    
    
    public void mover(int anchoV, int altoV, int dir) {
        if(y <= 0 || y >= anchoV - altoR)
            return;
        else{
            if(dir == 1)//abajo
                y += 5;
            else
                y -= 5;
            
            rect.setBounds(x,y,anchoR,altoR);
        }
    }
    
    
    
}
