/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package GameFrame;

/**
 *
 * @author geiver.botello
 */
public class Ball extends Movil{
    public static final int radio = 20;
    public int dx, dy;

    public Ball(int x, int y, int dx, int dy) {
        super();
        this.dx = dx;
        this.dy = dy;
        
        this.x = x;
        this.y = y;
        this.rect.setBounds(x,y,radio,radio);
    }
    

    public void mover(int anchoV, int altoV) {
        x += dx*4;
        y += dy*4;
        
        rect.setBounds(x,y,radio,radio);
    }
    
    public boolean isSubiendo(){
        if(dy >= 0)
            return false;
        else
            return true;
    }
    
    public void cambiarDireccion(int dx, int dy){
        this.dx = dx;
        this.dy = dy;
    }
    
}
